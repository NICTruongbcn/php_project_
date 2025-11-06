<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Helpers\AuthHelper;

class NoteController extends Controller
{
    public function create()
    {
        return view('notes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:normal,vocab,formula',
            'subject' => 'required_if:type,formula|string|max:255',
            'description' => 'nullable|string',
        ]);

        $note = Note::create([
            'user_id' => AuthHelper::id(),
            'title' => $request->title,
            'type' => $request->type,
            'subject' => $request->subject,
            'description' => $request->description,
            'is_private' => true,
            'is_completed' => false,
            'page_limit' => $request->type === 'normal' ? 100 : 50,
        ]);

        return redirect()->route('pages.create', $note->id)
                        ->with('success', 'Note created successfully! Now add your first page.');
    }

    public function show(Note $note)
    {
        if ($note->user_id !== AuthHelper::id()) {
            abort(403);
        }

        $pages = $note->pages()->orderBy('position')->get();
        return view('notes.show', compact('note', 'pages'));
    }

    public function edit(Note $note)
    {
        if ($note->user_id !== AuthHelper::id()) {
            abort(403);
        }

        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, Note $note)
    {
        if ($note->user_id !== AuthHelper::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $note->update($request->only(['title', 'description']));

        return redirect()->route('notes.show', $note->id)
                        ->with('success', 'Note updated successfully!');
    }

    public function destroy(Note $note)
    {
        if ($note->user_id !== AuthHelper::id()) {
            abort(403);
        }

        $note->delete();

        return redirect()->route('dashboard')
                        ->with('success', 'Note deleted successfully!');
    }
}