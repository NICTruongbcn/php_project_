@extends('layouts.app')

@section('title', 'Edit Page - MemoryMaster')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Page</h1>
            <p class="text-gray-600">Update the content of this page in "{{ $page->note->title }}"</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Page Form -->
        <form method="POST" action="{{ route('pages.update', $page->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Front Side -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Front Side</h3>
                        <span class="text-sm text-gray-500">Question / Term</span>
                    </div>
                    
                    <!-- Text Input -->
                    <div>
                        <label for="front_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Text Content
                        </label>
                        <textarea name="front_text" id="front_text" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                  placeholder="Enter the question, term, or front content">{{ old('front_text', $page->front_text) }}</textarea>
                    </div>

                    <!-- LaTeX Input -->
                    <div>
                        <label for="front_latex" class="block text-sm font-medium text-gray-700 mb-2">
                            LaTeX Formula (Optional)
                        </label>
                        <textarea name="front_latex" id="front_latex" rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors font-mono text-sm"
                                  placeholder="Enter LaTeX formula (e.g., E = mc^2)">{{ old('front_latex', $page->front_latex) }}</textarea>
                    </div>
                </div>

                <!-- Back Side -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Back Side</h3>
                        <span class="text-sm text-gray-500">Answer / Definition</span>
                    </div>
                    
                    <!-- Text Input -->
                    <div>
                        <label for="back_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Text Content
                        </label>
                        <textarea name="back_text" id="back_text" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                  placeholder="Enter the answer, definition, or back content">{{ old('back_text', $page->back_text) }}</textarea>
                    </div>

                    <!-- LaTeX Input -->
                    <div>
                        <label for="back_latex" class="block text-sm font-medium text-gray-700 mb-2">
                            LaTeX Formula (Optional)
                        </label>
                        <textarea name="back_latex" id="back_latex" rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors font-mono text-sm"
                                  placeholder="Enter LaTeX formula">{{ old('back_latex', $page->back_latex) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Additional Options -->
            <div class="border-t border-gray-200 pt-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Additional Options</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Difficulty -->
                    <div>
                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">
                            Difficulty Level
                        </label>
                        <select name="difficulty" id="difficulty"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @php
                                $meta = $page->meta ? json_decode($page->meta, true) : [];
                                $currentDifficulty = $meta['difficulty'] ?? 'medium';
                            @endphp
                            <option value="easy" {{ $currentDifficulty === 'easy' ? 'selected' : '' }}>Easy</option>
                            <option value="medium" {{ $currentDifficulty === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="hard" {{ $currentDifficulty === 'hard' ? 'selected' : '' }}>Hard</option>
                        </select>
                    </div>

                    <!-- Tags -->
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                            Tags (Optional)
                        </label>
                        @php
                            $currentTags = isset($meta['tags']) ? implode(', ', $meta['tags']) : '';
                        @endphp
                        <input type="text" name="tags" id="tags"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Enter tags separated by commas"
                               value="{{ old('tags', $currentTags) }}">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('notes.show', $page->note_id) }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    ← Back to Note
                </a>
                
                <div class="flex space-x-4">
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        Update Page
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection