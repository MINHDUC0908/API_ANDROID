<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Exception;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply_discount_code(Request $request)
    {
        try {
            $code_coupon = $request->input("coupon");

            // Tìm coupon theo mã
            $coupon = Coupon::where("code", $code_coupon)->first();

            if ($coupon) {
                // Kiểm tra tính hợp lệ của coupon
                if (!$coupon->isValid()) {
                    return response()->json([
                        'message' => "Mã giảm giá đã hết hạn"
                    ], 400);
                }
            
                // Kiểm tra số lượng coupon có còn hay không
                if ($coupon->quantity <= 0) {
                    return response()->json([
                        'message' => "Mã giảm giá đã hết lượt sử dụng"
                    ], 400);
                }            

                // Trả về thông tin coupon nếu coupon hợp lệ
                return response()->json([
                    'message' => "Áp dụng coupon thành công",
                    'data' => $coupon
                ]);
            } else {
                // Nếu coupon không tồn tại
                return response()->json([
                    'message' => "Mã coupon không hợp lệ",
                ], 400);
            }
        } catch (Exception $e) {
            // Xử lý lỗi
            return response()->json([
                'message' => "Lỗi khi áp dụng mã coupon",
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
