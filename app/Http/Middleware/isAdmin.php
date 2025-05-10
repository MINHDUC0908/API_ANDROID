<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Lấy user đã authenticate (ví dụ qua Sanctum hoặc guard mặc định)
        $user = $request->user();

        // Nếu chưa login hoặc không phải admin thì block
        if (!$user || $user->role !== 'admin') {
            // Nếu yêu cầu từ web (Accept header chứa text/html) thì redirect
            if ($request->expectsJson() === false) {
                return redirect()->route('admin.login')->with('error', 'Unauthorized. Only admins can access this resource.');
            }

            // Ngược lại (API hoặc AJAX) trả JSON
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only admins can access this resource.'
            ], 403);
        }

        return $next($request);
    }
}
