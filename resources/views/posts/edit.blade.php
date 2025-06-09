@extends('layouts.app')
@section('title', 'Edit Post')
@section('content')

<h2 class="text-2xl font-semibold mb-6">Edit Post</h2>

<form action="{{ route('posts.update', $post) }}" method="POST" class="max-w-lg">
  @csrf
  @method('PUT')

  <div class="mb-4">
    <label for="title" class="block font-semibold mb-1">Title</label>
    <input type="text" name="title" id="title" 
           class="w-full border border-gray-300 rounded px-3 py-2 @error('title') border-red-500 @enderror" 
           value="{{ old('title', $post->title) }}" required>
    @error('title')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
  </div>

  <div class="mb-4">
    <label for="content" class="block font-semibold mb-1">Content</label>
    <textarea name="content" id="content" rows="5" 
              class="w-full border border-gray-300 rounded px-3 py-2 @error('content') border-red-500 @enderror">{{ old('content', $post->content) }}</textarea>
    @error('content')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
  </div>

  <div class="mb-6">
    <label class="block font-semibold mb-1">Tags</label>
    <div class="flex flex-wrap gap-2">
      @foreach($tags as $tag)
        <label class="inline-flex items-center space-x-2">
          <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                 {{ in_array($tag->id, old('tags', $postTags)) ? 'checked' : '' }} 
                 class="form-checkbox h-5 w-5 text-indigo-600">
          <span class="text-gray-700">{{ $tag->name }}</span>
        </label>
      @endforeach
    </div>
  </div>

  <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded transition">Update</button>
  <a href="{{ route('posts.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
</form>

@endsection
