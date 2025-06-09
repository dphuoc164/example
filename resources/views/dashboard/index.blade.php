@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
  <!-- Welcome Banner -->
  <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-6 sm:p-8 shadow text-white">
    <h2 class="text-2xl sm:text-3xl font-semibold mb-2">Welcome, <span class="underline decoration-yellow-300">{{ auth()->user()->name }}</span></h2>
    <p class="text-sm sm:text-base mb-4">Role: <strong class="capitalize">{{ auth()->user()->role }}</strong></p>

    <div class="flex flex-wrap gap-3 mt-4">
      <a href="{{ route('posts.index') }}"
         class="inline-flex items-center gap-2 bg-white text-indigo-600 text-sm font-medium rounded-md px-4 py-2 shadow hover:bg-indigo-50 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5l-13 13" />
        </svg>
        Manage Posts
      </a>

      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit"
          class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md px-4 py-2 shadow">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7" />
          </svg>
          Logout
        </button>
      </form>
    </div>
  </div>

  <!-- Info Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-8">
    <div class="bg-white rounded-md shadow-sm p-5 flex items-center gap-4">
      <div class="bg-indigo-100 p-2 rounded-full">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 014-4h3" />
        </svg>
      </div>
      <div>
        <p class="text-sm text-gray-500">Total Posts</p>
        <p class="text-lg font-semibold text-gray-800">{{ number_format($postsCount ?? 0) }}</p>
      </div>
    </div>

    <div class="bg-white rounded-md shadow-sm p-5 flex items-center gap-4">
      <div class="bg-green-100 p-2 rounded-full">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 2l9 4-9 16-9-16 9-4z" />
        </svg>
      </div>
      <div>
        <p class="text-sm text-gray-500">Your Role</p>
        <p class="text-lg font-semibold text-gray-800 capitalize">{{ auth()->user()->role }}</p>
      </div>
    </div>
  </div>
</div>
@endsection
