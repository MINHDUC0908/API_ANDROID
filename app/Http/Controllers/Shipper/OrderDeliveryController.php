<?php

namespace App\Http\Controllers\Shipper;

use App\Http\Controllers\Controller;
use App\Models\DeliveryStatusHistory;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\Shipper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderDeliveryController extends Controller
{
    public function availableOrders()
    {
        try {
            $orders = Order::where('status', 'processing')
                ->whereDoesntHave('orderDeliveries', function($query) {
                    $query->whereNotIn('delivery_status', ['cancelled', 'rejected', 'failed']);
                })
                ->with(['user:id,name,phone', 'shippingAddress', 'orderItems'])
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        } catch (Exception $e)
        {
            return response()->json([
                'success' => false,
                "message" => "Lỗi khi cập nhật dữ liệu"
            ]);
        }
    }

     /**
     * Danh sách đơn hàng đang vận chuyển của shipper hiện tại
     */
    public function myCurrentDeliveries()
    {
        $shipper = Shipper::where('user_id', Auth::id())->firstOrFail();
        
        $deliveries = OrderDelivery::where('shipper_id', $shipper->id)
            ->whereIn('delivery_status', ['accepted', 'picked_up', 'in_transit'])
            ->with(['order.shippingAddress', 'order.user:id,name,phone', 'order.orderItems'])
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $deliveries
        ]);
    }

    
     /**
     * Nhận đơn hàng để vận chuyển
     */
    public function acceptOrder($id)
    {
        try {
            // Kiểm tra shipper
            $shipper_id = Auth::id();
            $shipper = Shipper::where('user_id', $shipper_id)->first();
            if (!$shipper) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy shipper cho tài khoản này'
                ], 404);
            }

            // Kiểm tra nếu shipper đang bận
            $activeDeliveries = OrderDelivery::where('shipper_id', $shipper->id)
                ->whereIn('delivery_status', ['accepted', 'picked_up', 'in_transit'])
                ->count();

            if ($activeDeliveries > 0 && $shipper->status == 'busy') {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đang có đơn hàng đang xử lý'
                ], 400);
            }

            // Kiểm tra đơn hàng
            $order = Order::find($id);
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đơn hàng với ID: ' . $id
                ], 404);
            }

            // Kiểm tra trạng thái đơn hàng
            if ($order->status !== 'processing') {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng không sẵn sàng để giao'
                ], 400);
            }

            // Kiểm tra xem đơn đã có người nhận chưa
            $existingDelivery = OrderDelivery::where('order_id', $id)
                ->whereIn('delivery_status', ['accepted', 'picked_up', 'in_transit'])
                ->first();

            if ($existingDelivery) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng đã được nhận bởi shipper khác'
                ], 400);
            }

            // Tạo đơn giao hàng mới
            $delivery = OrderDelivery::create([
                'order_id' => $id,
                'shipper_id' => $shipper->id,
                'delivery_status' => 'accepted',
                'accepted_at' => now()
            ]);

            // Cập nhật trạng thái đơn hàng
            $order->status = 'accepted';
            $order->save();

            // Cập nhật trạng thái shipper thành busy
            $shipper->status = 'busy';
            $shipper->save();

            // Ghi lại lịch sử
            DeliveryStatusHistory::create([
                'order_delivery_id' => $delivery->id,
                'status' => 'accepted',
                'notes' => 'Shipper đã nhận đơn và đang đến lấy hàng',
                'updated_by' => Auth::user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Nhận đơn hàng thành công',
                'data' => $delivery->load('order')
            ], 200);
        } catch (\Exception $e) {
            // Ghi log lỗi để debug
            Log::error('Lỗi khi nhận đơn hàng: ' . $e->getMessage(), [
                'order_id' => $id,
                'user_id' => Auth::user()->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi nhận đơn hàng'
            ], 500);
        }
    }
}
