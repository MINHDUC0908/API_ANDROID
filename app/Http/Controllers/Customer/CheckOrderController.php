<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOrderController extends Controller
{
    public function checkorder(Request $request)
    {
        try {
            $user = Auth::id();
            $payment_status = $request->input("payment_status");
    
            $order = Order::where('user_id', $user)
                ->when($payment_status, function ($query) use ($payment_status) {
                    $query->where('status', $payment_status);
                })
                // ->whereHas('orderItems.product', function ($query) {
                //     $query->whereNotNull('id');
                // })
                ->with([
                    'shippingAddress',
                    // 'orderItems.product.images', 
                    // 'orderItems.product.brand',
                    // 'orderItems.product.category'
                ])
                ->orderBy("id", "DESC")
                ->get();
    
            return response()->json([
                "message" => "Tải đơn hàng thành công",
                "data" => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }    
    public function orderItem(Request $request)
    {
        try {
            $userId = Auth::id();

            if (!$userId) {
                return response()->json([
                    "message" => "Vui lòng đăng nhập để sử dụng tài nguyên!!!"
                ], 401);
            }

            $orderNumber = $request->input("order_number");

            // Lấy đơn hàng của user đang đăng nhập kèm theo order items và các thông tin cần thiết
            $order = Order::where("order_number", $orderNumber)
                ->where("user_id", $userId)
                ->with([
                    'orderItems.product' => function ($query) {
                        $query->select('id', 'product_name', "price"); // chỉ lấy tên sản phẩm
                    },
                    'orderItems.product.images' => function ($query) {
                        $query->select('id', 'product_id', 'image_path')->limit(1); // chỉ lấy ảnh đầu tiên
                    },
                    'orderItems.color' => function ($query) {
                        $query->select('id', 'name'); // chỉ lấy tên màu
                    },
                ])
                ->first();

            if (!$order) {
                return response()->json([
                    "message" => "Không tìm thấy đơn hàng."
                ], 404);
            }

            return response()->json([
                "message" => "Lấy dữ liệu thành công.",
                "data" => $order->orderItems->map(function ($item) {
                    return [
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                        'product_name' => $item->product->product_name ?? null,
                        'product_image' => $item->product->images->first()->image_path ?? null,
                        'color_name' => $item->color->name ?? null,
                    ];
                })
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    // public function orderItem(Request $request)
    // {
    //     try {
    //         $userId = Auth::id();

    //         if (!$userId) {
    //             return response()->json([
    //                 "message" => "Vui lòng đăng nhập để sử dụng tài nguyên!!!"
    //             ], 401);
    //         }

    //         $orderNumber = $request->input("order_number");

    //         // Lấy đơn hàng của user đang đăng nhập kèm theo order items
    //         $order = Order::where("order_number", $orderNumber)
    //             ->where("user_id", $userId)
    //             ->with("orderItems.product", "orderItems.color", "orderItems.product.images") // load thêm quan hệ con nếu cần
    //             ->first();

    //         if (!$order) {
    //             return response()->json([
    //                 "message" => "Không tìm thấy đơn hàng."
    //             ], 404);
    //         }

    //         return response()->json([
    //             "message" => "Lấy dữ liệu thành công.",
    //             "data" => $order->orderItems
    //         ]);

    //     } catch (Exception $e) {
    //         return response()->json([
    //             'message' => 'Đã xảy ra lỗi.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
}
