<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\BrandController as CustomerBrandController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Customer\CheckOrderController;
use App\Http\Controllers\Customer\ContactController;
use App\Http\Controllers\Customer\CouponController as CustomerCouponController;
use App\Http\Controllers\Customer\LoyaltyController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\RatingController;
use App\Http\Controllers\Customer\ShiipingController;
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
    Route::get("product/{id}", [ProductController::class, 'show']);
    Route::post("colors/store", [ColorController::class, 'store']);
    Route::get("colors", [ColorController::class, 'index']);

    Route::post("image/store", [ImageController::class, 'store']);


    Route::get("/incrementProduct", [CustomerProductController::class, "incrementProduct"]);

    Route::get("/incrementBrand", [CustomerBrandController::class, 'index']);

    Route::get('/getProductWithBrand', [CustomerBrandController::class, 'getProductWithBrand']);



    Route::post("coupon/store", [CouponController::class, "store"]);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/user', [LoginController::class, 'user']);
    Route::post('/cart/store', [CartController::class, 'store']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/update/check/{id}', [CartController::class, 'checked']);
    Route::post('/cart/update/checkedAll', [CartController::class, 'checkedAll']);
    Route::post('/cart/update/quantity/{id}', [CartController::class, 'quantity']);
    Route::post('/cart/delete/{id}', [CartController::class, 'delete']);


    Route::post('/shipping/store', [ShiipingController::class, 'store']);
    Route::get('/shipping', [ShiipingController::class, 'index']);


    Route::post('/order/store', [OrderController::class, 'store']);

    Route::post("coupon/apply", [CustomerCouponController::class, 'apply_discount_code']);

    Route::post("updateUser/{id}", [ProfileController::class, 'update']);

    Route::post("uploadImage/{id}", [ProfileController::class, "uploadImage"]);

    Route::get("count", [CartController::class, "count"]);


    Route::get("checkorder", [CheckOrderController::class, "checkorder"]);
    Route::get("orderItem", [CheckOrderController::class, "orderItem"]);

    // Đổi điểm
    Route::get("loyalty", [LoyaltyController::class, "index"]);

    // Đánh giá
    Route::post("rating/store", [RatingController::class, "store"]);
});

Route::get("filter", [CustomerProductController::class, "filter"]);
Route::post("contact", [ContactController::class, "store"]);
Route::get("rating", [RatingController::class, "index"]);

// php artisan serve --host=192.168.1.98 --port=8000