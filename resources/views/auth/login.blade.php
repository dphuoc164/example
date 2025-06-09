@extends('layouts.app')
@section('title','Login')
@section('content')

<div class="flex min-h-screen">
  <!-- Left Side (Image or Banner) -->
  <div class="w-1/2 bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center text-white p-12">
    <div class="text-center space-y-4">
      <h1 class="text-4xl font-bold">Connect Tech</h1>
      <p class="text-lg text-purple-100">Nơi kết nối công nghệ và sáng tạo!</p>
      <img src="https://source.unsplash.com/400x300/?technology,network" alt="Tech Banner" class="rounded-lg shadow-lg mt-6">
    </div>
  </div>

  <!-- Right Side (Login Form) -->
  <div class="w-1/2 flex items-center justify-center bg-gray-100 p-12">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-10">
      <h2 class="text-3xl font-bold text-indigo-600 mb-6 text-center">Đăng nhập tài khoản</h2>

      @if($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
          <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('login') }}" method="POST" class="space-y-5">
        @csrf
        <div>
          <label for="email" class="block mb-1 font-semibold text-gray-700">Email</label>
          <input
            id="email"
            name="email"
            type="email"
            value="{{ old('email') }}"
            required
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
          />
        </div>

        <div>
          <label for="password" class="block mb-1 font-semibold text-gray-700">Mật khẩu</label>
          <input
            id="password"
            name="password"
            type="password"
            required
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
          />
        </div>

        <button type="submit"
          class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition">
          Đăng nhập
        </button>
      </form>

      <p class="mt-6 text-center text-sm text-gray-600">
        Chưa có tài khoản?
        <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:underline">Đăng ký ngay</a>
      </p>
    </div>
  </div>
</div>

@endsection
