<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashbroadController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RatingController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\VNPayController;
use Illuminate\Support\Facades\Route;



Route::get('/vnpay-return', [VNPayController::class, 'vnpayReturn'])->name('vnpay.return');
Route::get('/zalopay-return', [OrderController::class, 'zalopayReturn'])->name('zalopay.return');



Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});


Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get("/", [DashbroadController::class, "index"])->name("home");
    
    Route::prefix('brand')->name('brand.')->group(function () {
        Route::get('/list', [BrandController::class, 'index'])->name('list');
        Route::get('/create', [BrandController::class, 'create'])->name('create');
        Route::post('/create', [BrandController::class, 'store'])->name('store');
        Route::get('/show/{id}', [BrandController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [BrandController::class, 'edit'])->name('edit');
        Route::put('/edit/{id}', [BrandController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [BrandController::class, 'destroy'])->name('delete');
    });

    Route::prefix('category')->name('category.')->group(function() {
        Route::get('/list', [CategoryController::class, 'index'])->name('list');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/create', [CategoryController::class, 'store'])->name('store');
        Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::post('edit/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [CategoryController::class, 'destroy'])->name('delete');
    });

    Route::prefix('product')->name('product.')->group(function(){
        Route::get('/list', [ProductController::class, 'index'])->name('list');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/create', [ProductController::class, 'store'])->name('store');
        Route::get('brands/{category_id}', [ProductController::class, 'getBrandsByCategory']);
        Route::get('edit/{id}', [ProductController::class, 'edit'])->name('edit');
        Route::post('edit/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [ProductController::class, 'destroy'])->name('destroy');
        Route::get('show/{id}', [ProductController::class, 'show'])->name('show');
    });

    Route::prefix('orders')->name('orders.')->group(function(){
        Route::get('list', [AdminOrderController::class, 'order'])->name('list');
        Route::get('show/{id}', [AdminOrderController::class, 'show'])->name('show');
        Route::put('update/{id}', [AdminOrderController::class, 'updateStatus'])->name('updateStatus');
    });

    // Mã giảm giá
    Route::prefix("coupon")->name("coupon.")->group(function() {
        Route::get("index", [CouponController::class, 'index'])->name("index");
        Route::get("create", [CouponController::class, 'create'])->name("create");
        Route::post("create", [CouponController::class, "store"])->name("store");
        Route::get("edit/{id}", [CouponController::class, 'edit'])->name("edit");
        Route::put("edit/{id}", [CouponController::class, "update"])->name("update");
        Route::delete("delete/{id}", [CouponController::class, "destroy"])->name("destroy");
    });


    Route::prefix("discount")->name("discount.")->group(function() {
        Route::get("discount", [DiscountController::class, 'index'])->name("index");
        Route::post("store", [DiscountController::class, 'store'])->name("store");
        Route::put("update/{id}", [DiscountController::class, 'update'])->name("update");
        Route::delete("destroy/{id}", [DiscountController::class, 'destroy'])->name("destroy");
    });


    Route::prefix("rating")->name("rating.")->group(function() {
        Route::get("index", [RatingController::class, 'index'])->name("index");
        Route::get("ratingImage", [RatingController::class, 'ratingImage'])->name("ratingImage");
        Route::delete('/destroy/{id}', [RatingController::class, 'destroy'])->name('destroy');
    });


    Route::prefix('profile')->name('profile.')->group(function() {
        Route::get("index", [ProfileController::class, 'index'])->name('index'); 
        Route::put("update/{id}", [ProfileController::class, 'update'])->name('update');
        Route::put("image/{id}", [ProfileController::class, "image"])->name('image');
        Route::delete("image/{id}", [ProfileController::class, "deleteImage"])->name('deleteImage');
        Route::put("updatePassword/{id}", [ProfileController::class, 'updatePassword'])->name("updatePassword");
    });
    Route::resource('colors', ColorController::class);


    Route::get("logout", [LoginController::class, "logout"])->name("logout");

    Route::prefix('image')->name('image.')->group(function() {
        Route::get("index", [ImageController::class, 'index'])->name('index'); 
        Route::get("store", [ImageController::class, 'index'])->name('store'); 
        Route::get("update/{id}", [ImageController::class, 'index'])->name('update'); 
    });

    Route::prefix('staff')->name('staff.')->group(function() {
        Route::get("index", [StaffController::class, 'index'])->name('index'); 
        Route::get("create", [StaffController::class, 'create'])->name('create'); 
        Route::post("store", [StaffController::class, 'store'])->name('store'); 
        Route::get("edit", [StaffController::class, 'edit'])->name('edit'); 
        Route::delete("destroy", [StaffController::class, 'destroy'])->name('destroy'); 
    });
    Route::get('/api/statistics/monthly-revenue', [StatisticsController::class, 'monthlyRevenue']);
    Route::get('/api/statistics/top-products', [StatisticsController::class, 'topSellingProducts']);
    Route::get('/api/statistics/orderStatusStats', [StatisticsController::class, 'orderStatusStats']);
    Route::get('/api/statistics/weeklyRevenueStats', [StatisticsController::class, 'weeklyRevenueStats']);
    Route::get('/api/statistics/dailyRevenueStats', [StatisticsController::class, 'dailyRevenueStats']);
});

