@extends('layouts.app')
@section('title', 'Posts')
@section('content')

@if(session('success'))
  <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
    {{ session('success') }}
  </div>
@endif

@if(session('error'))
  <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
    {{ session('error') }}
  </div>
@endif

<form action="{{ route('posts.bulkDelete') }}" method="POST" onsubmit="return confirm('Are you sure to delete selected posts?');">
  @csrf
  @method('DELETE')

  <div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-semibold">Posts</h2>
    <div class="flex space-x-3">
      {{-- Tất cả tài khoản được phép tạo bài --}}
      @can('create', App\Models\Post::class)
        <a href="{{ route('posts.create') }}" 
           class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded transition">
          + New Post
        </a>
      @endcan

      {{-- Chỉ admin mới được bulk delete --}}
      @if(auth()->user()->hasRole('admin'))
        <button type="submit" 
          class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded transition"
          @if($posts->isEmpty()) disabled @endif>
          Bulk Delete
        </button>
      @endif
    </div>
  </div>

  <div class="overflow-x-auto bg-white rounded shadow border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          @if(auth()->user()->hasRole('admin'))
            <th class="p-3 text-left">
              <input id="select-all" type="checkbox" class="form-checkbox h-5 w-5 text-indigo-600">
            </th>
          @endif
          <th class="p-3 text-left text-sm font-medium text-gray-700">Title</th>
          <th class="p-3 text-left text-sm font-medium text-gray-700">Tags</th>
          <th class="p-3 text-left text-sm font-medium text-gray-700">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @forelse($posts as $post)
          <tr class="hover:bg-gray-50">
            @if(auth()->user()->hasRole('admin'))
              <td class="p-3">
                <input type="checkbox" name="ids[]" value="{{ $post->id }}" class="form-checkbox h-5 w-5 text-indigo-600">
              </td>
            @endif
            <td class="p-3 text-gray-900 font-semibold">{{ $post->title }}</td>
            <td class="p-3 text-gray-700">
              @foreach($post->tags as $tag)
                <span class="inline-block bg-indigo-200 text-indigo-800 text-xs px-2 py-0.5 rounded mr-1 mb-1">{{ $tag->name }}</span>
              @endforeach
            </td>
            <td class="p-3 space-x-3">
              {{-- Chỉ admin mới được sửa, content không --}}
              @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('posts.edit', $post) }}" 
                   class="text-indigo-600 hover:underline font-semibold">Edit</a>
              @endif

              {{-- Chỉ admin mới được xóa --}}
              @if(auth()->user()->hasRole('admin'))
                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure to delete this post?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-red-600 hover:underline font-semibold">Delete</button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="{{ auth()->user()->hasRole('admin') ? 4 : 3 }}" class="p-6 text-center text-gray-500">No posts found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ $posts->links() }}
  </div>
</form>

<script>
  document.getElementById('select-all')?.addEventListener('change', function() {
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = this.checked);
  });
</script>

@endsection
