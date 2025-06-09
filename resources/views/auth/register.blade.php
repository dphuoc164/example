@extends('layouts.app')
@section('title','Register')
@section('content')

<div class="flex min-h-screen">
  <!-- Left Side (Banner or Info) -->
  <div class="w-1/2 bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white p-12">
    <div class="text-center space-y-4">
      <h1 class="text-4xl font-bold">Chào mừng đến với Connect Tech</h1>
      <p class="text-lg text-green-100">Tham gia cộng đồng công nghệ, chia sẻ và phát triển!</p>
      <img src="https://source.unsplash.com/400x300/?technology,signup" alt="Tech Signup" class="rounded-lg shadow-lg mt-6">
    </div>
  </div>

  <!-- Right Side (Register Form) -->
  <div class="w-1/2 flex items-center justify-center bg-gray-100 p-12">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-10">
      <h2 class="text-3xl font-bold text-green-600 mb-6 text-center">Tạo tài khoản mới</h2>

      @if($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
          <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('register') }}" method="POST" class="space-y-5">
        @csrf

        <div>
          <label for="name" class="block mb-1 font-semibold text-gray-700">Họ và tên</label>
          <input
            name="name"
            type="text"
            id="name"
            value="{{ old('name') }}"
            required
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
          />
        </div>

        <div>
          <label for="email" class="block mb-1 font-semibold text-gray-700">Email</label>
          <input
            name="email"
            type="email"
            id="email"
            value="{{ old('email') }}"
            required
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
          />
        </div>

        <div>
          <label for="password" class="block mb-1 font-semibold text-gray-700">Mật khẩu</label>
          <input
            name="password"
            type="password"
            id="password"
            required
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
          />
        </div>

        <div>
          <label for="password_confirmation" class="block mb-1 font-semibold text-gray-700">Xác nhận mật khẩu</label>
          <input
            name="password_confirmation"
            type="password"
            id="password_confirmation"
            required
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400"
          />
        </div>

        <button type="submit"
          class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition">
          Đăng ký
        </button>
      </form>

      <p class="mt-6 text-center text-sm text-gray-600">
        Đã có tài khoản?
        <a href="{{ route('login') }}" class="text-green-600 font-semibold hover:underline">Đăng nhập ngay</a>
      </p>
    </div>
  </div>
</div>

@endsection
