<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\LoginHistory;

class AuthController extends Controller
{
    // protected $except = [
    //     'login',
    //     'api/*'  // Bỏ qua tất cả route bắt đầu bằng api/
    // ];

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {

        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (Auth::attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                
                // Ghi lại lịch sử đăng nhập
                LoginHistory::create([
                    'user_id' => Auth::id(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'login_at' => now(),
                    'status' => 'success',
                ]);

                return response()->json([
                    'success' => true,
                    'redirect' => route('dashboard.index')
                ]);
            }

            return response()->json([
                'success' => false,
                'errors' => [
                    'email' => ['Thông tin đăng nhập không chính xác']
                ]
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra'
            ], 500);
        }
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','confirmed','min:6'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'content',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard.index')->with('success', 'Đăng ký thành công. Bạn đã được đăng nhập.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
