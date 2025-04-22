<?php

use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\VNPayController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/vnpay-return', [VNPayController::class, 'vnpayReturn'])->name('vnpay.return');
Route::get('/zalopay-return', [OrderController::class, 'zalopayReturn'])->name('zalopay.return');


Route::get('/test-email', function () {
    try {
        Log::info('Testing email to: duclvm.23itb@vku.udn.vn');
        Mail::raw('This is a test email from Laravel', function ($message) {
            $message->to('duclvm.23itb@vku.udn.vn')
                    ->subject('Test Email');
        });
        Log::info('Test email sent');
        return response()->json(['message' => 'Test email sent']);
    } catch (\Exception $e) {
        Log::error('Error sending test email: ' . $e->getMessage());
        return response()->json([
            'message' => 'Error sending test email',
            'error' => $e->getMessage()
        ], 500);
    }
});