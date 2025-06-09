<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;

// Trang gốc → chuyển về login
Route::get('/', fn() => redirect('/login'));

// ======= AUTH ROUTES =======
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.perform');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ======= PROTECTED ROUTES (chỉ khi đã đăng nhập) =======
Route::middleware('auth')->group(function () {

    // Dashboard chính
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Lịch sử đăng nhập (chỉ admin)
    Route::get('/login-history', [DashboardController::class, 'loginHistory'])
        ->middleware('role:admin')
        ->name('dashboard.login_history');

    // Nhật ký hoạt động (chỉ admin)
    Route::get('/activity-logs', [DashboardController::class, 'activityLogs'])
        ->middleware('role:admin')
        ->name('dashboard.activityLogs');

    // ======= POSTS =======

    // Chức năng chỉ dành riêng cho content (tạo bài viết)
    Route::middleware('role:content')->group(function () {
        Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    });

    // Các chức năng còn lại chỉ dành cho admin (index, show, edit, update, delete)
    Route::middleware('role:admin')->group(function () {
        Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
        Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
        Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

        // Xóa nhiều bài viết cùng lúc (bulk delete)
        Route::delete('/posts/bulk-delete', [PostController::class, 'bulkDelete'])->name('posts.bulkDelete');
    });
});
