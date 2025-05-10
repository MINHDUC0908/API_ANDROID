<?php

use App\Http\Controllers\Shipper\OrderDeliveryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'isShipper']) // hoặc 'isShipper'
    ->prefix('shipper/')
    ->group(function () {
        Route::get("availableOrders", [OrderDeliveryController::class, "availableOrders"]);
        Route::post("orders/{id}", [OrderDeliveryController::class, "acceptOrder"]);
        Route::post("myCurrentDeliveries/{id}", [OrderDeliveryController::class, "myCurrentDeliveries"]);
});
