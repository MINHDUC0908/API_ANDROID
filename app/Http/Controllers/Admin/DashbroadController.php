<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\RecentActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashbroadController extends Controller
{
    public function index()
    {
        if (!Auth::check())
        {
            return redirect()->route('login');
        }
        $name = Auth::user()->name;
        $user = User::count();  
        $product = Product::count();
        $order = Order::all();
        $countOrder = Order::whereIn('status', ['Completed','Cancel'])->count();

        $colors  = Color::where("quantity", "<", 10)->with(['product', 'images'])->take(5)->get();
        $recentUsers = User::orderBy('created_at', 'desc')->where('role', 'user')
        ->take(5)
        ->get();
        // Đơn hàng gần đây
        $recentOrders = Order::with('user')
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

        // Sản phẩm bán chạy
        // Sử dụng withSum để tính tổng số lượng đã bán
        // Sắp xếp theo tổng số lượng đã bán giảm dần và lấy 5 sản phẩm đầu tiên
        $topProducts = Product::with('images')
            ->withSum('orderItems as total_sold', 'quantity')
            ->orderByDesc('total_sold')
            ->take(8)
            ->get();


        // Sự kiện 
        $recent_activities = RecentActivity::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($activity) {
                $data = $activity->activity_data;
                $activity->action = isset($data['action']) ? json_decode('"' . $data['action'] . '"') : null;
                $activity->product_name = $data['product_name'] ?? null;
                $activity->product_id = $data['product_id'] ?? null;
                $activity->order_code = $data['order_code'] ?? null;
                $activity->user_id = $data['user_id'] ?? null;
                $activity->name = $data['name'] ?? null;
                return $activity;
            });
        
        // Sản phẩm chưa được bán
        $unsoldProducts = Product::whereDoesntHave('orderItems', function ($query) {
            $query->whereNotNull('product_id');
                })->with(['category', 'images', 'colors',"discount"])->withAvg('rating', 'rating')
                ->has('colors')
                ->get();
    
        return view("admin.dashboard", compact('name', 'user', 'product', 'order', 'countOrder', 'colors','recentUsers', "recentOrders", "topProducts", "recent_activities", 'unsoldProducts'));
    }
}
