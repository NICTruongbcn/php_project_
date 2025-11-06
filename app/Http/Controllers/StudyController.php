<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\StudySession;
use App\Models\SessionQueueItem;
use App\Models\Review;
use App\Models\Page;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\AuthHelper;

class StudyController extends Controller
{
    public function show(Note $note)
    {
        if ($note->user_id !== AuthHelper::id()) {
            abort(403);
        }

        if ($note->pages()->count() === 0) {
            return redirect()->route('notes.show', $note->id)
                            ->with('error', 'Please add some pages to this note before studying.');
        }

        $studyMethods = $this->getStudyMethods();
        return view('study.show', compact('note', 'studyMethods'));
    }

    public function start(Request $request, Note $note)
    {
        if ($note->user_id !== AuthHelper::id()) {
            abort(403);
        }

        $request->validate([
            'method' => 'required|in:SM2,Leitner,Pomodoro,Custom',
            'study_time' => 'required|integer|min:5|max:120',
            'break_time' => 'required|integer|min:1|max:30',
        ]);

        // Create study session
        $session = StudySession::create([
            'user_id' => AuthHelper::id(),
            'note_id' => $note->id,
            'method' => $request->method,
            'config' => json_encode([
                'study_time' => $request->study_time,
                'break_time' => $request->break_time,
                'intervals' => $this->getIntervals($request->method),
            ]),
            'started_at' => now(),
        ]);

        // Create queue items from pages
        $pages = $note->pages()->orderBy('position')->get();
        foreach ($pages as $index => $page) {
            SessionQueueItem::create([
                'session_id' => $session->id,
                'page_id' => $page->id,
                'queue_position' => $index + 1,
                'status' => 'pending',
                'next_review_at' => now(),
            ]);
        }

        return redirect()->route('study.session', $session)
                        ->with('success', 'Study session started!');
    }

    public function session(StudySession $session)
    {
        if ($session->user_id !== AuthHelper::id()) {
            abort(403);
        }

        // Get current queue item
        $currentItem = $session->queueItems()
                            ->where(function($query) {
                                $query->where('next_review_at', '<=', now())
                                      ->orWhereNull('next_review_at');
                            })
                            ->where('status', '!=', 'done')
                            ->orderBy('queue_position')
                            ->first();

        if (!$currentItem) {
            // All items are done
            return $this->completeSession($session);
        }

        $page = $currentItem->page;
        $studyMethods = $this->getStudyMethods();
        $methodConfig = $studyMethods[$session->method] ?? $studyMethods['SM2'];

        return view('study.session', compact('session', 'currentItem', 'page', 'methodConfig'));
    }

    public function review(Request $request, StudySession $session)
    {
        if ($session->user_id !== AuthHelper::id()) {
            abort(403);
        }

        $request->validate([
            'page_id' => 'required|exists:pages,id',
            'quality' => 'required|integer|min:0|max:5',
            'response_time' => 'required|integer',
        ]);

        // Create review record
        Review::create([
            'user_id' => AuthHelper::id(),
            'page_id' => $request->page_id,
            'session_id' => $session->id,
            'quality' => $request->quality,
            'response_time_sec' => $request->response_time,
        ]);

        // Update queue item based on SM-2 algorithm
        $queueItem = SessionQueueItem::where('session_id', $session->id)
                                    ->where('page_id', $request->page_id)
                                    ->first();

        if ($queueItem) {
            $this->updateQueueItem($queueItem, $request->quality);
        }

        // Check if session is complete
        $remainingItems = $session->queueItems()
                                ->where('status', '!=', 'done')
                                ->where(function($query) {
                                    $query->where('next_review_at', '<=', now())
                                          ->orWhereNull('next_review_at');
                                })
                                ->count();

        if ($remainingItems === 0) {
            return response()->json([
                'success' => true,
                'completed' => true,
                'redirect_url' => route('study.complete', $session)
            ]);
        }

        return response()->json([
            'success' => true,
            'completed' => false
        ]);
    }

    public function complete(StudySession $session)
    {
        if ($session->user_id !== AuthHelper::id()) {
            abort(403);
        }

        $session->update([
            'ended_at' => now(),
            'total_seconds' => now()->diffInSeconds($session->started_at),
        ]);

        // Calculate statistics
        $totalReviews = $session->reviews()->count();
        $averageQuality = $session->reviews()->avg('quality') ?? 0;
        $totalTime = $session->total_seconds;

        return view('study.complete', compact('session', 'totalReviews', 'averageQuality', 'totalTime'));
    }

    public function break(StudySession $session)
    {
        if ($session->user_id !== AuthHelper::id()) {
            abort(403);
        }

        $config = json_decode($session->config, true);
        $breakTime = $config['break_time'] ?? 5;

        return view('study.break', compact('session', 'breakTime'));
    }

    private function getStudyMethods()
    {
        return [
            'SM2' => [
                'name' => 'SuperMemo SM-2',
                'description' => 'Spaced repetition algorithm for optimal memory retention',
                'default_study_time' => 25,
                'default_break_time' => 5,
                'intervals' => [1, 6, 16, 35, 62],
                'color' => 'blue',
                'icon' => 'fas fa-brain',
            ],
            'Leitner' => [
                'name' => 'Leitner System',
                'description' => 'Box-based system for progressive learning',
                'default_study_time' => 30,
                'default_break_time' => 10,
                'intervals' => [1, 2, 5, 10, 20],
                'color' => 'green',
                'icon' => 'fas fa-layer-group',
            ],
            'Pomodoro' => [
                'name' => 'Pomodoro Technique',
                'description' => 'Time management with focused intervals',
                'default_study_time' => 25,
                'default_break_time' => 5,
                'intervals' => [1, 7, 16],
                'color' => 'red',
                'icon' => 'fas fa-clock',
            ],
            'Custom' => [
                'name' => 'Custom Method',
                'description' => 'Create your own study intervals',
                'default_study_time' => 20,
                'default_break_time' => 5,
                'intervals' => [1, 3, 7, 14, 30],
                'color' => 'purple',
                'icon' => 'fas fa-cog',
            ],
        ];
    }

    private function getIntervals($method)
    {
        $methods = $this->getStudyMethods();
        return $methods[$method]['intervals'] ?? [1, 3, 7, 14, 30];
    }

    private function updateQueueItem($queueItem, $quality)
    {
        // SM-2 Algorithm implementation
        if ($quality < 3) {
            // Failed - reset repetitions, interval = 1 day
            $queueItem->update([
                'repetition_count' => 0,
                'interval_days' => 1,
                'next_review_at' => now()->addDay(),
                'status' => 'again',
                'last_quality' => $quality,
                'last_reviewed_at' => now(),
            ]);
        } else {
            // Passed - update ease factor and interval
            $newRepetition = $queueItem->repetition_count + 1;
            
            if ($newRepetition == 1) {
                $interval = 1;
            } elseif ($newRepetition == 2) {
                $interval = 6;
            } else {
                $interval = round($queueItem->interval_days * $queueItem->ease_factor);
            }

            // Update ease factor (SM-2 formula)
            $newEase = $queueItem->ease_factor + (0.1 - (5 - $quality) * (0.08 + (5 - $quality) * 0.02));
            $newEase = max(1.3, $newEase); // Minimum ease factor

            $queueItem->update([
                'repetition_count' => $newRepetition,
                'interval_days' => $interval,
                'ease_factor' => $newEase,
                'next_review_at' => now()->addDays($interval),
                'status' => 'done',
                'last_quality' => $quality,
                'last_reviewed_at' => now(),
            ]);
        }
    }

    private function completeSession($session)
    {
        $session->update([
            'ended_at' => now(),
            'total_seconds' => now()->diffInSeconds($session->started_at),
        ]);

        return redirect()->route('study.complete', $session);
    }
}