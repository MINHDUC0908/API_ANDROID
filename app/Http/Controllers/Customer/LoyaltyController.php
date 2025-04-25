<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyPoint;
use App\Models\PointTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoyaltyController extends Controller
{
    public function handleSuccessfulPayment($totalPrice, $pointCode)
    {
        $earnedPoints = floor($totalPrice / 10000); // 10.000 VNĐ = 1 điểm

        // 2. Lấy thông tin người dùng hiện tại
        $user = Auth::id();

        // 3. Lấy thông tin điểm hiện tại của người dùng
        $loyalty = LoyaltyPoint::firstOrCreate(['user_id' => $user]);

        // 4. Kiểm tra xem người dùng có đủ điểm để sử dụng hay không
        if ($pointCode > 0 && $loyalty->total_points >= $pointCode) {
            // Nếu có đủ điểm, trừ đi điểm người dùng sử dụng
            $loyalty->decrement('total_points', $pointCode);  // Trừ điểm đã sử dụng từ tổng điểm

            // Ghi lại giao dịch trừ điểm
            PointTransaction::create([
                'user_id' => $user,
                'type' => 'redeem', // Loại giao dịch: redeem (dùng điểm)
                'points' => -$pointCode,
                'description' => 'Sử dụng điểm để thanh toán, đã trừ ' . $pointCode . ' điểm.',
            ]);

            // Trừ số tiền tương ứng với số điểm đã sử dụng
            $totalPrice -= ($pointCode * 10000);  // 1 điểm = 10.000 VNĐ

            // Kiểm tra nếu tổng giá trị thanh toán còn lại sau khi dùng điểm <= 0 thì có thể cho miễn phí
            if ($totalPrice < 0) {
                $totalPrice = 0;
            }
        }

        // 5. Cập nhật điểm cho người dùng (cộng điểm earnedPoints)
        $loyalty->increment('total_points', $earnedPoints);
        $loyalty->increment('lifetime_points', $earnedPoints);

        // 6. Ghi lại lịch sử giao dịch điểm (cộng điểm)
        PointTransaction::create([
            'user_id' => $user,
            'type' => 'earn', // Loại giao dịch: earn (cộng điểm)
            'points' => $earnedPoints,
            'description' => 'Thanh toán đơn hàng thành công, tổng tiền: ' . number_format($totalPrice) . ' VNĐ.',
        ]);

        // 7. Trả về thông báo thành công
        return response()->json([
            'message' => "Bạn đã nhận được $earnedPoints điểm cho đơn hàng này! Tổng giá trị thanh toán sau khi sử dụng điểm là " . number_format($totalPrice) . " VNĐ."
        ]);
    }
}

