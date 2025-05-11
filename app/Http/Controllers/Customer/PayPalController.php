<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class PayPalController extends Controller
{
    private $client;

    public function __construct()
    {
        $clientId = config('paypal.client_id');
        $clientSecret = config('paypal.client_secret');
        $environment = new SandboxEnvironment($clientId, $clientSecret);
        $this->client = new PayPalHttpClient($environment);
    }

    public function createOrder($order, $total)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "USD",
                    "value" => (string) $total 
                ]
            ]],
            "application_context" => [
                "cancel_url" => route('paypal.cancel', ['order_number' => $order->order_number]),
                'return_url' => route('paypal.success', ['order_number' => $order->order_number]),
            ]
        ];

        try {
            $response = $this->client->execute($request);
            $approvalLink = collect($response->result->links)->firstWhere('rel', 'approve')->href;
            // Lưu thông tin thanh toán vào cơ sở dữ liệu
            Payment::create([
                'order_id' => $order->id,
                'payment_gateway' => 'PayPal',
                'transaction_id' => $order->order_number,
                'amount' => $total,
                'status' => 'pending'  // Trạng thái chờ xử lý
            ]);
            return response()->json([
                'status' => 'success',
                'paypal_url' => $approvalLink
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function success(Request $request)
    {
        try {
            $orderId = $request->query('order_number');
            $order = Order::where('order_number', $orderId)->first();
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }
            $order->status = 'Processing';
            $order->payment_status = 'paid';
            $order->save();
            $payment = Payment::where('transaction_id', $orderId)->first();
            Log::debug($payment);
            if (!$payment) {
                return response()->json(['error' => 'Payment not found'], 404);
            }
            if ($payment) {
                $payment->status = 'success';
                $payment->save();
            }
            // Xóa các cartItems đã được thanh toán
            $user_id = $order->user_id;
            $cart = Cart::where('user_id', $user_id)->first();
            if ($cart) {
                $cart->cartItems()->where("checked", 1)->delete();
            }
            return redirect()->away("http://localhost:5173/payment/success?transaction_id=$orderId&total_amount={$order->total_amount}");
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function cancel(Request $request)
    {
        try {
            $orderId = $request->query('order_number');
            if (!$orderId) {
                return response()->json(['error' => 'Order number not provided'], 400);
            }
            $order = Order::where('order_number', $orderId)->first();
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }
            // Xóa đơn hàng
            $order->delete();
            // Xóa thông tin thanh toán liên quan
            $payment = Payment::where('transaction_id', $orderId)->first();
            if ($payment) {
                $payment->delete();
            }
            // Xóa các cartItems đã được thanh toán
            return redirect()->away("http://localhost:5173/payment/failed?transaction_id=$orderId&total_amount={$order->total_amount}");
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
