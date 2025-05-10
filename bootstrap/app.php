<?php

use App\Http\Middleware\EnsureAuthenticated;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\IsShipper;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        api: __DIR__.'/../routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth:sanctum' => EnsureAuthenticated::class, // Tạo alias 'auth'
            'isAdmin' => isAdmin::class, // Đăng ký alias isAdmin ở đây
            'isShipper' => IsShipper::class, 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (RouteNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Bạn cần đăng nhập để truy cập tài nguyên này',
                ], 401);
            }
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
        });
    })->create();
