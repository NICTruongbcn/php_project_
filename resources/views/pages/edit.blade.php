@extends('layouts.app')

@section('title', 'Edit Page - MemoryMaster')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Page</h1>
            <p class="text-gray-600">Update the content of this page in "{{ $page->note->title }}"</p>
            <div class="mt-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    @if($page->note->type === 'normal') bg-blue-100 text-blue-800
                    @elseif($page->note->type === 'vocab') bg-green-100 text-green-800
                    @else bg-purple-100 text-purple-800 @endif">
                    <i class="fas 
                        @if($page->note->type === 'normal') fa-sticky-note
                        @elseif($page->note->type === 'vocab') fa-book
                        @else fa-square-root-alt @endif mr-2"></i>
                    {{ ucfirst($page->note->type) }} Note
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Page Form -->
        <form method="POST" action="{{ route('pages.update', $page->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Front Side -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Front Side</h3>
                        <span class="text-sm text-gray-500">
                            @if($page->note->type === 'vocab') Term 
                            @elseif($page->note->type === 'formula') Formula 
                            @else Title @endif
                        </span>
                    </div>
                    
                    <!-- Text Input -->
                    <div>
                        <label for="front_text" class="block text-sm font-medium text-gray-700 mb-2">
                            @if($page->note->type === 'vocab') Term *
                            @elseif($page->note->type === 'formula') Formula Description
                            @else Title * @endif
                        </label>
                        @if($page->note->type === 'vocab')
                        <input type="text" name="front_text" id="front_text" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Enter the term or word"
                               value="{{ old('front_text', $page->front_text) }}">
                        @else
                        <textarea name="front_text" id="front_text" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                  placeholder="@if($page->note->type === 'formula') Describe the formula @else Enter the title @endif"
                                  @if($page->note->type !== 'formula') required @endif>{{ old('front_text', $page->front_text) }}</textarea>
                        @endif
                    </div>

                    <!-- LaTeX Input -->
                    @if($page->note->type === 'formula')
                    <div>
                        <label for="front_latex" class="block text-sm font-medium text-gray-700 mb-2">
                            LaTeX Formula (Optional)
                        </label>
                        <textarea name="front_latex" id="front_latex" rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors font-mono text-sm"
                                  placeholder="Enter LaTeX formula (e.g., E = mc^2)">{{ old('front_latex', $page->front_latex) }}</textarea>
                    </div>
                    @endif

                    <!-- Image Upload -->
                    <div>
                        <label for="front_image" class="block text-sm font-medium text-gray-700 mb-2">
                            @if($page->note->type === 'formula') Formula Image
                            @else Front Image @endif (Optional)
                        </label>
                        <input type="file" name="front_image" id="front_image" 
                               accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @if($page->front_image)
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 mb-1">Current image:</p>
                            <img src="{{ Storage::url($page->front_image) }}" 
                                 alt="Current front image" 
                                 class="max-w-xs h-auto rounded-lg border border-gray-300">
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Back Side -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Back Side</h3>
                        <span class="text-sm text-gray-500">
                            @if($page->note->type === 'vocab') Definition
                            @elseif($page->note->type === 'formula') Explanation
                            @else Content @endif
                        </span>
                    </div>
                    
                    <!-- Text Input -->
                    <div>
                        <label for="back_text" class="block text-sm font-medium text-gray-700 mb-2">
                            @if($page->note->type === 'vocab') Definition *
                            @elseif($page->note->type === 'formula') Formula Explanation
                            @else Content * @endif
                        </label>
                        <textarea name="back_text" id="back_text" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                  placeholder="@if($page->note->type === 'vocab') Enter the definition @elseif($page->note->type === 'formula') Explain the formula @else Enter the content @endif"
                                  @if($page->note->type !== 'formula') required @endif>{{ old('back_text', $page->back_text) }}</textarea>
                    </div>

                    <!-- LaTeX Input -->
                    @if($page->note->type === 'formula')
                    <div>
                        <label for="back_latex" class="block text-sm font-medium text-gray-700 mb-2">
                            LaTeX Formula (Optional)        
                        </label>
                        <textarea name="back_latex" id="back_latex" rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors font-mono text-sm"
                                  placeholder="Enter LaTeX formula">{{ old('back_latex', $page->back_latex) }}</textarea>
                    </div>
                    @endif

                    <!-- Image Upload -->
                    <div>
                        <label for="back_image" class="block text-sm font-medium text-gray-700 mb-2">
                            @if($page->note->type === 'formula') Explanation Image
                            @else Back Image @endif (Optional)
                        </label>
                        <input type="file" name="back_image" id="back_image" 
                               accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @if($page->back_image)
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 mb-1">Current image:</p>
                            <img src="{{ Storage::url($page->back_image) }}" 
                                 alt="Current back image" 
                                 class="max-w-xs h-auto rounded-lg border border-gray-300">
                        </div>
                        @endif
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