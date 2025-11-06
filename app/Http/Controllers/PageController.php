<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Helpers\AuthHelper;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function create(Note $note)
    {
        if ($note->user_id !== AuthHelper::id()) {
            abort(403);
        }

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

        $currentPages = $note->pages()->count();
        if ($currentPages >= $note->page_limit && !AuthHelper::isVip()) {
            return redirect()->route('notes.show', $note->id)
                            ->with('error', 'Page limit reached. Upgrade to VIP to add more pages.');
        }

        // Validation rules based on note type
        $validationRules = [
            'front_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'back_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'difficulty' => 'required|in:easy,medium,hard',
            'tags' => 'nullable|string|max:255',
        ];

        // Add specific validation based on note type
        switch ($note->type) {
            case 'vocab':
                $validationRules['front_text'] = 'required|string|max:255';
                $validationRules['back_text'] = 'required|string|max:1000';
                break;
            
            case 'formula':
                $validationRules['front_text'] = 'nullable|string|max:255';
                $validationRules['back_text'] = 'nullable|string|max:1000';
                $validationRules['front_latex'] = 'nullable|string|max:1000';
                $validationRules['back_latex'] = 'nullable|string|max:1000';
                // For formula notes, require at least one field to be filled
                break;
            
            case 'normal':
            default:
                $validationRules['front_text'] = 'required|string|max:1000';
                $validationRules['back_text'] = 'required|string|max:1000';
                break;
        }

        $request->validate($validationRules);

        // Additional validation for formula notes
        if ($note->type === 'formula') {
            if (empty($request->front_text) && empty($request->front_latex) && !$request->hasFile('front_image')) {
                return redirect()->back()->withErrors([
                    'front_text' => 'Please fill at least one field for front side (text, LaTeX, or image).'
                ]);
            }
        }

        $position = $note->pages()->max('position') + 1;

        // Handle image uploads
        $frontImagePath = null;
        $backImagePath = null;

        if ($request->hasFile('front_image')) {
            $frontImagePath = $request->file('front_image')->store('pages', 'public');
        }

        if ($request->hasFile('back_image')) {
            $backImagePath = $request->file('back_image')->store('pages', 'public');
        }

        // Prepare meta data
        $meta = [
            'difficulty' => $request->difficulty ?? 'medium',
            'tags' => $request->tags ? array_map('trim', explode(',', $request->tags)) : [],
        ];

        // Add type-specific meta data
        if ($note->type === 'vocab') {
            $meta['word_type'] = $request->word_type ?? 'general';
        }

        Page::create([
            'note_id' => $note->id,
            'position' => $position,
            'front_text' => $request->front_text,
            'back_text' => $request->back_text,
            'front_latex' => $request->front_latex,
            'back_latex' => $request->back_latex,
            'front_image' => $frontImagePath,
            'back_image' => $backImagePath,
            'source' => 'user',
            'meta' => json_encode($meta),
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

        $note = $page->note;

        // Validation rules based on note type
        $validationRules = [
            'front_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'back_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'difficulty' => 'required|in:easy,medium,hard',
            'tags' => 'nullable|string|max:255',
        ];

        switch ($note->type) {
            case 'vocab':
                $validationRules['front_text'] = 'required|string|max:255';
                $validationRules['back_text'] = 'required|string|max:1000';
                break;
            
            case 'formula':
                $validationRules['front_text'] = 'nullable|string|max:255';
                $validationRules['back_text'] = 'nullable|string|max:1000';
                $validationRules['front_latex'] = 'nullable|string|max:1000';
                $validationRules['back_latex'] = 'nullable|string|max:1000';
                break;
            
            case 'normal':
            default:
                $validationRules['front_text'] = 'required|string|max:1000';
                $validationRules['back_text'] = 'required|string|max:1000';
                break;
        }

        $request->validate($validationRules);

        // Additional validation for formula notes
        if ($note->type === 'formula') {
            if (empty($request->front_text) && empty($request->front_latex) && !$request->hasFile('front_image')) {
                return redirect()->back()->withErrors([
                    'front_text' => 'Please fill at least one field for front side (text, LaTeX, or image).'
                ]);
            }
        }

        // Handle image uploads
        $frontImagePath = $page->front_image;
        $backImagePath = $page->back_image;

        if ($request->hasFile('front_image')) {
            // Delete old image
            if ($frontImagePath) {
                Storage::disk('public')->delete($frontImagePath);
            }
            $frontImagePath = $request->file('front_image')->store('pages', 'public');
        }

        if ($request->hasFile('back_image')) {
            // Delete old image
            if ($backImagePath) {
                Storage::disk('public')->delete($backImagePath);
            }
            $backImagePath = $request->file('back_image')->store('pages', 'public');
        }

        // Prepare meta data
        $meta = [
            'difficulty' => $request->difficulty ?? 'medium',
            'tags' => $request->tags ? array_map('trim', explode(',', $request->tags)) : [],
        ];

        // Add type-specific meta data
        if ($note->type === 'vocab') {
            $meta['word_type'] = $request->word_type ?? 'general';
        }

        $page->update([
            'front_text' => $request->front_text,
            'back_text' => $request->back_text,
            'front_latex' => $request->front_latex,
            'back_latex' => $request->back_latex,
            'front_image' => $frontImagePath,
            'back_image' => $backImagePath,
            'meta' => json_encode($meta),
        ]);

        return redirect()->route('notes.show', $page->note_id)
                        ->with('success', 'Page updated successfully!');
    }

    public function destroy(Page $page)
    {
        if ($page->note->user_id !== AuthHelper::id()) {
            abort(403);
        }

        // Delete images
        if ($page->front_image) {
            Storage::disk('public')->delete($page->front_image);
        }
        if ($page->back_image) {
            Storage::disk('public')->delete($page->back_image);
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