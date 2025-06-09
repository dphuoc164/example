<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $roles): Response
    {
        if (! $request->content()) {
            abort(403, 'Bạn cần đăng nhập để truy cập.');
        }

        $rolesArray = array_map('trim', explode(',', $roles));

        if (! in_array($request->user()->role, $rolesArray)) {
            abort(403, 'Bạn không có quyền truy cập.');
        }

        return $next($request);
    }
}
