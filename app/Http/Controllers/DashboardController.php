<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginHistory;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard.index');
    }

    public function loginHistory()
    {
        // Cho cả admin và content đều xem được
        return view('dashboard.login_history', [
            'histories' => LoginHistory::with('user')->orderBy('login_at', 'desc')->paginate(20)
        ]);
    }

    public function activityLogs()
    {
        // Kiểm tra phân quyền tại đây, nếu không phải admin thì abort 403
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        $logs = ActivityLog::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('dashboard.activity-logs', compact('logs'));
    }
}
