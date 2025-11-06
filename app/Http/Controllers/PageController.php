<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Helpers\AuthHelper;

class PageController extends Controller
{
    public function create(Note $note)
    {
        if ($note->user_id !== AuthHelper::id()) {
            abort(403);
        }

        // Check page limit
        $currentPages = $note->pages()->count();
        if ($currentPages >= $note->page_limit && !AuthHelper::isVip()) {
            return redirect()->route('notes.show', $note->id)
                            ->with('error', 'Page limit reached. Upgrade to VIP to add more pages.');
        }

        return view('pages.create', compact('note'));
    }

    public function store(Request $request, Note $note)
    {
        if ($note->user_id !== AuthHelper::id()) {
            abort(403);
        }

        // Check page limit
        $currentPages = $note->pages()->count();
        if ($currentPages >= $note->page_limit && !AuthHelper::isVip()) {
            return redirect()->route('notes.show', $note->id)
                            ->with('error', 'Page limit reached. Upgrade to VIP to add more pages.');
        }

        $request->validate([
            'front_text' => 'required_without:front_latex|string|max:1000',
            'back_text' => 'required_without:back_latex|string|max:1000',
            'front_latex' => 'required_without:front_text|string|max:1000',
            'back_latex' => 'required_without:back_text|string|max:1000',
        ]);

        $position = $note->pages()->max('position') + 1;

        Page::create([
            'note_id' => $note->id,
            'position' => $position,
            'front_text' => $request->front_text,
            'back_text' => $request->back_text,
            'front_latex' => $request->front_latex,
            'back_latex' => $request->back_latex,
            'source' => 'user',
            'meta' => json_encode([
                'difficulty' => $request->difficulty ?? 'medium',
                'tags' => $request->tags ? explode(',', $request->tags) : [],
            ]),
        ]);

        $message = 'Page added successfully!';

        // Check if reached limit
        if ($currentPages + 1 >= $note->page_limit) {
            $message .= ' Note is now complete!';
            $note->update(['is_completed' => true]);
        }

        // Decide where to redirect based on user action
        if ($request->has('add_another')) {
            return redirect()->route('pages.create', $note->id)
                            ->with('success', $message);
        } else {
            return redirect()->route('notes.show', $note->id)
                            ->with('success', $message);
        }
    }

    public function edit(Page $page)
    {
        if ($page->note->user_id !== AuthHelper::id()) {
            abort(403);
        }

        return view('pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        if ($page->note->user_id !== AuthHelper::id()) {
            abort(403);
        }

        $request->validate([
            'front_text' => 'required_without:front_latex|string|max:1000',
            'back_text' => 'required_without:back_latex|string|max:1000',
            'front_latex' => 'required_without:front_text|string|max:1000',
            'back_latex' => 'required_without:back_text|string|max:1000',
        ]);

        $page->update([
            'front_text' => $request->front_text,
            'back_text' => $request->back_text,
            'front_latex' => $request->front_latex,
            'back_latex' => $request->back_latex,
            'meta' => json_encode([
                'difficulty' => $request->difficulty ?? 'medium',
                'tags' => $request->tags ? explode(',', $request->tags) : [],
            ]),
        ]);

        return redirect()->route('notes.show', $page->note_id)
                        ->with('success', 'Page updated successfully!');
    }

    public function destroy(Page $page)
    {
        if ($page->note->user_id !== AuthHelper::id()) {
            abort(403);
        }

        $noteId = $page->note_id;
        $page->delete();

        // Reorder positions
        $pages = Page::where('note_id', $noteId)->orderBy('position')->get();
        foreach ($pages as $index => $pageItem) {
            $pageItem->update(['position' => $index + 1]);
        }

        // Check if note should be marked as incomplete
        $remainingPages = Page::where('note_id', $noteId)->count();
        $note = Note::find($noteId);
        if ($remainingPages < $note->page_limit) {
            $note->update(['is_completed' => false]);
        }

        return redirect()->route('notes.show', $noteId)
                        ->with('success', 'Page deleted successfully!');
    }

    public function reorder(Request $request, Note $note)
    {
        if ($note->user_id !== AuthHelper::id()) {
            abort(403);
        }

        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:pages,id',
        ]);

        foreach ($request->order as $position => $pageId) {
            Page::where('id', $pageId)->update(['position' => $position + 1]);
        }

        return response()->json(['success' => true]);
    }
}