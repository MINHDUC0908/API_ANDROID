<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API!',
    ]);
});

Route::post('/register', [ RegisterController::class, 'register']);
Route::post('/login', [ LoginController::class, 'login']);
Route::group(['prefix' => 'admin'], function () {
    Route::post('/categories/store', [CategoryController::class, 'store']);
    Route::get('/brands', [BrandController::class, 'index']);
    Route::post('/brands/store', [BrandController::class, 'store']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get("products", [ProductController::class, 'index']);
    Route::post("products/store", [ProductController::class, 'store']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/user', [LoginController::class, 'user']);
});