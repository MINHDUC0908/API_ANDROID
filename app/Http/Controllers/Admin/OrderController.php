<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function order(Request $request)
    {
        $status = $request->input('status', 'pending');
        $orders = Order::where('status', $status)->orderBy("created_at", "DESC")->with(['user'])->paginate(25);
        return view('admin.order.order', compact('orders', 'status'));
    }
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->input('status');
        // if ($order->status == "Delivering")
        // {
        //     Mail::to($order->customer->email)->send(new MailOrderStatusUpdated($order));
        // }
        $order->save();
        return redirect()->back()->with('success', 'Trạng thái đơn hàng đã được cập nhật');
    }
    public function show($id)
    {
        $order = Order::with(['payments', 'user', "orderItems.product.images", 'orderItems.product.discount'])->findOrFail($id);
    
        return view('admin.order.show', compact('order'));
    }    
}
