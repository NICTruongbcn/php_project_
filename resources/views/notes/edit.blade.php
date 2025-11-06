@extends('layouts.app')

@section('title', 'Edit Note - MemoryMaster')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Note</h1>
            <p class="text-gray-600">Update your note details.</p>
        </div>

        <form method="POST" action="{{ route('notes.update', $note->id) }}">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Note Title *
                    </label>
                    <input type="text" name="title" id="title" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           value="{{ old('title', $note->title) }}">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description (Optional)
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                              placeholder="Describe what this note contains">{{ old('description', $note->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Read-only Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-2">Note Information</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Type:</span> {{ ucfirst($note->type) }}
                        </div>
                        <div>
                            <span class="font-medium">Pages:</span> {{ $note->pages()->count() }}/{{ $note->page_limit }}
                        </div>
                        <div>
                            <span class="font-medium">Created:</span> {{ $note->created_at->format('M d, Y') }}
                        </div>
                        <div>
                            <span class="font-medium">Status:</span> 
                            <span class="{{ $note->is_completed ? 'text-green-600' : 'text-yellow-600' }} font-medium">
                                {{ $note->is_completed ? 'Completed' : 'In Progress' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                <div class="flex space-x-4">
                    <a href="{{ route('notes.show', $note->id) }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <form method="POST" action="{{ route('notes.destroy', $note->id) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this note and all its pages?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Delete Note
                        </button>
                    </form>
                </div>
                
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    Update Note
                </button>
            </div>
        </form>
    </div>
</div>
@endsection