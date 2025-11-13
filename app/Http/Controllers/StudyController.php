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
use Illuminate\Support\Facades\DB;

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


        $nextReviewSession = $this->getNextReviewSession($note);
        $canStudyNow = $this->canStudyNow($note);

        $studyMethods = $this->getStudyMethods();
        return view('study.show', compact('note', 'studyMethods', 'nextReviewSession', 'canStudyNow'));
    }

    public function start(Request $request, Note $note)
    {
        if ($note->user_id !== AuthHelper::id()) {
            abort(403);
        }

        if (!$this->canStudyNow($note)) {
            return redirect()->back()
                            ->with('error', 'Study session is locked until the next review date.');
        }

        $request->validate([
            'study_method' => 'required|in:SM2,Leitner,Pomodoro,Custom',
            'study_time' => 'required|integer|min:5|max:120',
            'break_time' => 'required|integer|min:1|max:30',
        ]);

        $startedAt = Carbon::now();

        $session = StudySession::create([
            'user_id' => AuthHelper::id(),
            'note_id' => $note->id,
            'method' => $request->study_method,
            'config' => json_encode([
                'study_time' => $request->study_time,
                'break_time' => $request->break_time,
                'intervals' => $this->getIntervals($request->study_method),
            ]),
            'started_at' => $startedAt,
        ]);

        $pages = $note->pages()->orderBy('position')->get()->shuffle();
        foreach ($pages as $index => $page) {
            SessionQueueItem::create([
                'session_id' => $session->id,
                'page_id' => $page->id,
                'queue_position' => $index + 1,
                'status' => 'pending',
                'next_review_at' => $startedAt,
                'ease_factor' => 2.5,
                'interval_days' => 0,
                'repetition_count' => 0,
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

        if ($session->ended_at) {
            return redirect()->route('study.complete', $session);
        }

        $now = Carbon::now();
        $started = $session->started_at;
        
        if (!$started || $started->gt($now)) {
            $session->update(['started_at' => $now]);
            $started = $now;
        }

        $currentItem = $session->queueItems()
                            ->where(function($query) {
                                $query->where('status', 'pending')
                                      ->orWhere('status', 'again');
                            })
                            ->where(function($query) {
                                $query->where('next_review_at', '<=', now())
                                      ->orWhereNull('next_review_at');
                            })
                            ->orderBy('queue_position')
                            ->first();

        if (!$currentItem) {
            $session->update([
                'ended_at' => $now,
                'total_seconds' => $now->diffInSeconds($started),
            ]);
            return redirect()->route('study.complete', $session);
        }

        $page = $currentItem->page;
        $note = $page->note;
        $studyMethods = $this->getStudyMethods();
        $methodConfig = $studyMethods[$session->method] ?? $studyMethods['SM2'];

        $elapsedSeconds = $now->diffInSeconds($started);
        $totalMinutes = floor($elapsedSeconds / 60);
        $totalSeconds = $elapsedSeconds % 60;

        return view('study.session', compact(
            'session', 
            'currentItem', 
            'page', 
            'note', 
            'methodConfig',
            'totalMinutes',
            'totalSeconds'
        ));
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

        Review::create([
            'user_id' => AuthHelper::id(),
            'page_id' => $request->page_id,
            'session_id' => $session->id,
            'quality' => $request->quality,
            'response_time_sec' => $request->response_time,
        ]);

        $queueItem = SessionQueueItem::where('session_id', $session->id)
                                    ->where('page_id', $request->page_id)
                                    ->first();

        if ($queueItem) {
            $this->updateQueueItem($queueItem, $request->quality, $session);
        }

        $remainingItems = $session->queueItems()
                                ->where(function($query) {
                                    $query->where('status', 'pending')
                                          ->orWhere('status', 'again');
                                })
                                ->where(function($query) {
                                    $query->where('next_review_at', '<=', now())
                                          ->orWhereNull('next_review_at');
                                })
                                ->count();

        if ($remainingItems === 0) {
            $session->update([
                'ended_at' => now(),
                'total_seconds' => now()->diffInSeconds($session->started_at),
            ]);

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

        if (!$session->ended_at) {
            $now = Carbon::now();
            $started = $session->started_at;
            
            if (!$started || $started->gt($now)) {
                $started = $session->created_at ?? $now->subMinutes(30);
                $session->update(['started_at' => $started]);
            }

            $totalSeconds = $now->diffInSeconds($started);
            
            $totalSeconds = max(60, $totalSeconds);
            
            $session->update([
                'ended_at' => $now,
                'total_seconds' => $totalSeconds,
            ]);
        }

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

    private function updateQueueItem($queueItem, $quality, $session)
    {
        if ($quality < 3) {
            $maxPosition = $session->queueItems()->max('queue_position') ?? 0;
            
            $queueItem->update([
                'repetition_count' => 0,
                'interval_days' => 1,
                'ease_factor' => max(1.3, $queueItem->ease_factor - 0.2),
                'status' => 'again',
                'queue_position' => $maxPosition + 1,
                'next_review_at' => now()->addMinutes(10), 
                'last_quality' => $quality,
                'last_reviewed_at' => now(),
            ]);
        } else {
            $newRepetition = $queueItem->repetition_count + 1;
            
            $intervals = [1, 2, 4, 8, 16, 30]; 
            
            if ($newRepetition <= count($intervals)) {
                $interval = $intervals[$newRepetition - 1];
            } else {
                $interval = round($queueItem->interval_days * $queueItem->ease_factor);
            }

            $newEase = $queueItem->ease_factor + (0.1 - (5 - $quality) * (0.08 + (5 - $quality) * 0.02));
            $newEase = max(1.3, min(2.5, $newEase)); 

            $queueItem->update([
                'repetition_count' => $newRepetition,
                'interval_days' => $interval,
                'ease_factor' => $newEase,
                'status' => 'done',
                'next_review_at' => now()->addDays($interval),
                'last_quality' => $quality,
                'last_reviewed_at' => now(),
            ]);
        }
    }
    
    public function reviewSessions()
    {
        $user_id = AuthHelper::id();
        
        $reviewSessions = StudySession::where('user_id', $user_id)
            ->whereHas('queueItems', function($query) {
                $query->where('next_review_at', '<=', now())
                      ->where('status', 'done');
            })
            ->withCount(['queueItems as due_count' => function($query) {
                $query->where('next_review_at', '<=', now())
                      ->where('status', 'done');
            }])
            ->with('note')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('study.review-sessions', compact('reviewSessions'));
    }

    public function startReview(StudySession $session)
    {
        if ($session->user_id !== AuthHelper::id()) {
            abort(403);
        }

        $session->queueItems()
            ->where('next_review_at', '<=', now())
            ->where('status', 'done')
            ->update([
                'status' => 'pending',
                'queue_position' => DB::raw('queue_position + 1000') 
            ]);

        return redirect()->route('study.session', $session)
                        ->with('success', 'Review session started!');
    }

    private function getNextReviewSession(Note $note)
    {
        return SessionQueueItem::whereHas('session', function($query) use ($note) {
                $query->where('note_id', $note->id)
                      ->where('user_id', AuthHelper::id());
            })
            ->where('status', 'done')
            ->where('next_review_at', '>', now())
            ->orderBy('next_review_at', 'asc')
            ->first();
    }

    private function canStudyNow(Note $note)
    {
        $dueItems = SessionQueueItem::whereHas('session', function($query) use ($note) {
                $query->where('note_id', $note->id)
                      ->where('user_id', AuthHelper::id());
            })
            ->where(function($query) {
                $query->where('status', 'pending')
                      ->orWhere('status', 'again')
                      ->orWhere(function($q) {
                          $q->where('status', 'done')
                            ->where('next_review_at', '<=', now());
                      });
            })
            ->exists();

        return $dueItems;
    }
}