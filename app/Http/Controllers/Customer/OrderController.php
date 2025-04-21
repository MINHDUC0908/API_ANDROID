<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\PaymentSuccess;
use App\Models\Cart;
use App\Models\CartItem;
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

use function PHPUnit\Framework\returnSelf;

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
                return $this->processVNPay($order, $totalPrice);
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

    public function processVNPay($order, $totalPrice)
    {
        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Url = config("vnpay.url");
        $vnp_ReturnUrl = route('vnpay.return');  // URL trả về sau khi thanh toán
    

        // Tạo dữ liệu cho request thanh toán
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $totalPrice * 100,  // Số tiền thanh toán (VND)
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => "Thanh toán đơn hàng: " . $order->order_number,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $order->order_number,  // Mã đơn hàng
        );
    
        // Sắp xếp lại các tham số theo thứ tự alphabe
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
    
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
    
        // Tạo URL thanh toán
        $vnp_Url = $vnp_Url . "?" . $query;
    
        // Tính toán vnp_SecureHash
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
    
        // Lưu thông tin thanh toán vào cơ sở dữ liệu
        Payment::create([
            'order_id' => $order->id,
            'payment_gateway' => 'VNPay',
            'transaction_id' => $order->order_number,
            'amount' => $totalPrice,
            'status' => 'pending'  // Trạng thái chờ xử lý
        ]);
    
        // Trả về URL của VNPay để khách hàng thanh toán
        return response()->json([
            'status' => 'success',
            'vnpay_url' => $vnp_Url
        ]);
    }
    public function vnpayReturn(Request $request)
    {
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = array();

        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);

        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $vnp_HashSecret = "G6RRX221335F3YUNDITPW1UO6BIBSRH1";
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash == $vnp_SecureHash) {
            $vnp_ResponseCode = $request->vnp_ResponseCode;
            $vnp_TxnRef = $request->vnp_TxnRef;

            if ($vnp_ResponseCode == '00') {
                $order = Order::where('order_number', $vnp_TxnRef)->first();
                if ($order) {
                    $order->payment_status = 'paid';
                    $order->status = 'completed';
                    $order->save();

                    $payment = Payment::where('transaction_id', $vnp_TxnRef)->first();
                    if ($payment) {
                        $payment->status = 'success';
                        $payment->save();
                    }
                    // Xóa các cartItems đã được thanh toán
                    $user_id = $order->user_id;
                    Log::debug($user_id);
                    $cart = Cart::where('user_id', $user_id)->first();
                    if ($cart) {
                        $cart->cartItems()->where("checked", 1)->delete();
                    }

                    return redirect()->away("http://localhost:5173/payment/success?transaction_id=$vnp_TxnRef&total_amount={$order->total}");
                } else {
                    return redirect()->away("http://localhost:5173/payment/failed?message=Đơn hàng không tồn tại");
                }
            } else {
                $order = Order::where('order_number', $vnp_TxnRef)->first();
                if ($order) {
                    // Xóa đơn hàng
                    $order->delete();

                    // Xóa thông tin thanh toán liên quan
                    $payment = Payment::where('transaction_id', $vnp_TxnRef)->first();
                    if ($payment) {
                        $payment->delete();
                    }

                    return redirect()->away("http://localhost:5173/payment/failed?message=Thanh toán thất bại");
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Đơn hàng không tồn tại'
                    ]);
                }
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ'
            ]);
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
