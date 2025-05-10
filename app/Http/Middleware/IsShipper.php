<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IsShipper
{
    public function handle(Request $request, Closure $next)
    {
        Log::debug(Auth::user()->id);
        if ($request->user() && $request->user()->role === 'shipper') {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Only shippers can access this resource.'
        ], 403);
    }
}
