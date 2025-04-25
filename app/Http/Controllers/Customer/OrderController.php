<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\PaymentSuccess;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\LoyaltyPoint;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user_id = Auth::id();
            if (!$user_id) {
                return response()->json(['message' => 'Người dùng chưa đăng nhập'], 401);
            }
    
            $shipping_id = $request->input('shipping_id');
            $shippingAddress = ShippingAddress::where('user_id', $user_id)
                ->where('id', $shipping_id)
                ->first();
    
            if (!$shippingAddress) {
                return response()->json([
                    'message' => 'Địa chỉ giao hàng không hợp lệ'
                ], 422);
            }
    
            $cart = Cart::where('user_id', $user_id)->first();
            if (!$cart) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không có sản phẩm trong giỏ hàng'
                ], 400);
            }
    
            $cartItems = $cart->cartItems()->with('product', 'color')->where("checked", 1)->get();
    
            $totalPrice = $cartItems->sum('total');
            if ($totalPrice <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Giá trị đơn hàng không hợp lệ'
                ], 400);
            }
    
            $payment_method = $request->input('payment_method');


            // Mã giảm giá
            $couponCode = $request->input('coupon'); // Mã giảm giá (nếu có)
            $discount = 0;
            $coupon = null;
            if ($couponCode)
            {
                $coupon = Coupon::where('code', $couponCode)
                        ->where('expires_at', '>', now())
                        ->first();
                if (!$coupon) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn!'
                    ], 400);
                }
                // Tính số tiền giảm giá, không vượt quá tổng giá trị đơn hàng
                $discount = min($coupon->discount_amount, $totalPrice);

                // Cập nhật tổng giá trị sau khi giảm giá
                $totalPrice -= $discount;
            }
            $pointCode = $request->input("point");
            if ($pointCode)
            {
                $point = LoyaltyPoint::where("total_points", "<", $pointCode)->first();
                if ($point)
                {
                    return response()->json([
                        "message" => "Bạn không đủ điểm để quy đổi!!!",
                    ]);
                }
                $total_point = $pointCode * 0.01; // Quy đổi 1 điểm = 0.01 giá trị tiền
                $totalPrice -= $total_point; // Trừ điểm vào tổng giá trị thanh toán
            }
            // Khởi tạo biến $order
            $order = null;
    
            // Bắt đầu giao dịch
            DB::beginTransaction();
    
            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $user_id,
                "order_number" => 'DH' . time(),
                'shipping_address_id' => $shipping_id,
                'total_amount' => $totalPrice,
                'payment_method' => $payment_method,
                'status' => 'pending',
                "payment_status" => 'unpaid',
                'coupon_id' => $coupon ? $coupon->id : null,
                "discount_amount" => $discount,
            ]);
    
            // Xử lý OrderItem và cập nhật số lượng màu sắc
            foreach ($cartItems as $item) {
                if ($item->product) {
                    $product = $item->product;
    
                    if ($item->color_id) {
                        $color = $product->colors()->where('id', $item->color_id)->first();
    
                        if (!$color || $color->quantity < $item->quantity) {
                            DB::rollBack();
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Số lượng màu sắc không đủ'
                            ], 400);
                        }
    
                        $color->quantity -= $item->quantity;
                        $color->save();
    
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $item->product_id,
                            'color_id' => $item->color_id,
                            'quantity' => $item->quantity,
                        ]);
                    }
                }
            }
    
            if ($payment_method === "offline") {

                // Nếu có sử dụng mã giảm giá thì giảm quantity
                if ($coupon) {
                    $coupon->decrement('quantity'); // Trừ đi 1
                }

                // Tích điểm
                $loyalty = new LoyaltyController();
                $loyalty->handleSuccessfulPayment($totalPrice, $pointCode);
                
                $cartItemsArray = $cartItems->toArray();
                $cart->cartItems()->where("checked", 1)->delete();
                // Gửi email qua hàng đợi
                Log::info('Sending email to: ' . $order->user->email); // Debug email
                Mail::to($order->user->email)->queue(new PaymentSuccess($order, $cartItemsArray, $totalPrice));
                DB::commit();
    
                return response()->json([
                    'status' => 'success',
                    'message' => 'Đặt hàng thành công',
                    'data' => $order
                ], 200);
            } else if ($payment_method === "vnpay") {
                DB::commit();
                $vnpay = new VNPayController();
                return $vnpay->processVNPay($order, $totalPrice);
                // return $this->processVNPay($order, $totalPrice);
            } else if ($payment_method === "zalopay") 
            {
                DB::commit();
                return $this->processZaloPay($order, $totalPrice, $user_id);
            } else {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Phương thức thanh toán không hợp lệ'
                ], 400);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Đã xảy ra lỗi',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function processZaloPay($order, $totalPrice, $user_id)
    {
        try {
            $app_id = config('zalopay.app_id');
            $key1 = config('zalopay.key1');
            $endpoint = config('zalopay.endpoint');
            
            $user = User::find($user_id);

            // Cập nhật lại order_number
            $app_trans_id = now()->format('ymd') . '_' . $order->id . rand(1000, 9999);
            $order->order_number = $app_trans_id;
            $order->save();

            $app_time = round(microtime(true) * 1000);

            $embed_data = [
                "redirecturl" => route("zalopay.return")
            ];

            $item = json_encode([]);

            $data = implode("|", [
                $app_id,
                $app_trans_id,
                $user->email,
                $totalPrice,
                $app_time,
                json_encode($embed_data),
                $item
            ]);

            $mac = hash_hmac("sha256", $data, $key1);

            $params = [
                "app_id" => $app_id,
                "app_trans_id" => $app_trans_id,
                "app_user" => $user->email,
                "app_time" => $app_time,
                "amount" => (int)$totalPrice,
                "item" => $item,
                "embed_data" => json_encode($embed_data),
                "description" => "Thanh toán đơn hàng #" . $order->order_number,
                "bank_code" => "",
                "callback_url" => route("zalopay.return"),
                "mac" => $mac
            ];

            // Lưu thông tin thanh toán
            Payment::create([
                'order_id' => $order->id,
                'payment_gateway' => 'ZaloPay',
                'transaction_id' => $order->order_number,
                'amount' => $totalPrice,
                'status' => 'pending'
            ]);

            $response = Http::asForm()->post($endpoint, $params);
            $result = $response->json();

            if (isset($result['return_code']) && $result['return_code'] == 1) {
                return response()->json([
                    'status' => 'success',
                    'zalopay_url' => $result['order_url'] ?? null,
                    'app_trans_id' => $app_trans_id
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => $result['return_message'] ?? 'Không thể tạo thanh toán ZaloPay'
            ], 400);
        } catch (\Exception $e) {
            Log::error('ZaloPay Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi khi tạo thanh toán ZaloPay'
            ], 500);
        }
    }

    public function zalopayReturn(Request $request)
    {
        try {
            $app_id = config('zalopay.app_id');
            $key1 = config('zalopay.key1');
            $endpoint = config('zalopay.endpoint');

            $app_trans_id = $request->input('apptransid');
            if (!$app_trans_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Thiếu thông tin giao dịch'
                ], 400);
            }

            // Tạo dữ liệu gửi đi và mã hóa MAC
            $data = [
                "app_id" => $app_id,
                "app_trans_id" => $app_trans_id
            ];
            $data_string = "{$app_id}|{$app_trans_id}|{$key1}";
            $data['mac'] = hash_hmac("sha256", $data_string, $key1);

            // Gửi yêu cầu kiểm tra trạng thái giao dịch
            $response = Http::asForm()->post($endpoint, $data);
            $result = $response->json();


            $order = Order::where('order_number', $app_trans_id)->first();
            
            // Nếu giao dịch thành công
            if ($response->successful() && $result['return_code'] == 1) {
                if ($order) {
                    $order->payment_status = 'paid';
                    $order->status = 'completed';
                    $order->save();

                    $payment = Payment::where('transaction_id', $app_trans_id)->first();
                    if ($payment) {
                        $payment->status = 'success';
                        $payment->save();
                    }
                    // Xóa các sản phẩm đã thanh toán khỏi giỏ hàng
                    $cart = Cart::where('user_id', $order->user_id)->first();
                    if ($cart) {
                        $cart->cartItems()->where("checked", 1)->delete();
                    }
                    return redirect()->away("http://localhost:5173/payment/success?transaction_id=$app_trans_id&total_amount={$order->total}");
                }
                return redirect()->away("http://localhost:5173/payment/failed?message=Không tìm thấy đơn hàng");
            }

            // Nếu thất bại, xóa đơn hàng và payment nếu có
            if ($order) {
                $order->delete();
            }
            return response()->json([
                'message' => "Thanh toán thất bại"
            ]);
        } catch (\Exception $e) {
            Log::error("ZaloPay return error: " . $e->getMessage());

            return redirect()->away("http://localhost:5173/payment/failed?message=Có lỗi xảy ra khi xử lý thanh toán");
        }
    }
}
