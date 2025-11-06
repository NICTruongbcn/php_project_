@extends('layouts.app')

@section('title', $note->title . ' - MemoryMaster')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    @if($note->type === 'vocab')
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-green-600"></i>
                        </div>
                    @elseif($note->type === 'formula')
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-square-root-alt text-purple-600"></i>
                        </div>
                    @else
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-sticky-note text-blue-600"></i>
                        </div>
                    @endif
                    <h1 class="text-3xl font-bold text-gray-800">{{ $note->title }}</h1>
                </div>
                
                @if($note->description)
                    <p class="text-gray-600">{{ $note->description }}</p>
                @endif
                
                <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                    <span class="flex items-center">
                        <i class="fas fa-layer-group mr-1"></i>
                        {{ $pages->count() }}/{{ $note->page_limit }} pages
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-calendar mr-1"></i>
                        Created {{ $note->created_at->format('M d, Y') }}
                    </span>
                    @if($note->subject)
                        <span class="flex items-center">
                            <i class="fas fa-tag mr-1"></i>
                            {{ ucfirst($note->subject) }}
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="flex space-x-3">
                @if(!$note->is_completed)
                    <a href="{{ route('pages.create', $note->id) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        + Add Page
                    </a>
                @endif
                <a href="{{ route('study.show', $note->id) }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    Start Studying
                </a>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mt-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Progress</span>
                <span class="text-sm text-gray-500">{{ $pages->count() }}/{{ $note->page_limit }} pages</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" 
                     style="width: {{ min(($pages->count() / $note->page_limit) * 100, 100) }}%"></div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Pages List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-800">Pages ({{ $pages->count() }})</h2>
        </div>

        @if($pages->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($pages as $page)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-sm font-medium">
                                        #{{ $page->position }}
                                    </span>
                                    @if($page->meta && isset(json_decode($page->meta, true)['difficulty']))
                                        @php
                                            $difficulty = json_decode($page->meta, true)['difficulty'];
                                            $colorClasses = [
                                                'easy' => 'bg-green-100 text-green-800',
                                                'medium' => 'bg-yellow-100 text-yellow-800',
                                                'hard' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="text-xs font-semibold px-2 py-1 rounded {{ $colorClasses[$difficulty] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($difficulty) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Front Content -->
                                    <div>
                                        <h4 class="font-semibold text-gray-700 mb-2 text-sm">Front</h4>
                                        <div class="text-gray-800">
                                            @if($page->front_text)
                                                <p>{{ $page->front_text }}</p>
                                            @endif
                                            @if($page->front_latex)
                                                <div class="mt-2 bg-gray-100 p-3 rounded font-mono text-sm">
                                                    {{ $page->front_latex }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Back Content -->
                                    <div>
                                        <h4 class="font-semibold text-gray-700 mb-2 text-sm">Back</h4>
                                        <div class="text-gray-800">
                                            @if($page->back_text)
                                                <p>{{ $page->back_text }}</p>
                                            @endif
                                            @if($page->back_latex)
                                                <div class="mt-2 bg-gray-100 p-3 rounded font-mono text-sm">
                                                    {{ $page->back_latex }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($page->meta && isset(json_decode($page->meta, true)['tags']))
                                    @php
                                        $tags = json_decode($page->meta, true)['tags'];
                                    @endphp
                                    @if(!empty($tags))
                                        <div class="mt-3 flex flex-wrap gap-1">
                                            @foreach($tags as $tag)
                                                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">
                                                    {{ $tag }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <div class="flex space-x-2 ml-4">
                                <a href="{{ route('pages.edit', $page->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('pages.destroy', $page->id) }}" 
                                      class="inline" onsubmit="return confirm('Are you sure you want to delete this page?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-sticky-note text-gray-400 text-2xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-600 mb-2">No pages yet</h4>
                <p class="text-gray-500 mb-6">Start by adding your first page to this note</p>
                <a href="{{ route('pages.create', $note->id) }}" 
                   class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    Add First Page
                </a>
            </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between items-center mt-6">
        <a href="{{ route('dashboard') }}" 
           class="text-blue-600 hover:text-blue-800 transition-colors font-semibold">
            ← Back to Dashboard
        </a>
        
        <div class="flex space-x-3">
            <a href="{{ route('notes.edit', $note->id) }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                Edit Note
            </a>
            @if($pages->count() > 0)
                <a href="{{ route('study.show', $note->id) }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    Start Studying
                </a>
            @endif
        </div>
    </div>
</div>
@endsection