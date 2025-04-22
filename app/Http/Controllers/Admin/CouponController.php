<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Exception;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function store(Request  $request)
    {
        try {
            $coupon = new Coupon();
            $coupon->code = $request->input("code");
            $coupon->discount_amount = $request->input("discount_amount");
            $coupon->expires_at = $request->input("expires_at");
            $coupon->quantity = $request->input('quantity');
            $coupon->save();
            return response()->json([
                "message" => "Thêm phiếu giảm giá thành công",
                "data" => $coupon
            ]);
        } catch (Exception $e)
        {
            return response()->json([
                "message" => "Error creating coupon",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
