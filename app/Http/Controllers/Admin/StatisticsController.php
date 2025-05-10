<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function monthlyRevenue()
    {
        // Tạo danh sách đủ 12 tháng (1 - 12)
        $months = range(1, 12);
    
        // Lấy dữ liệu doanh thu từ đơn hàng
        $data = DB::table('orders')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('revenue', 'month') // Lấy dữ liệu dưới dạng key-value
            ->toArray();
    
        // Tạo mảng đầy đủ 12 tháng, nếu tháng nào không có doanh thu thì đặt là 0
        $fullData = array_map(fn($month) => [
            'month' => $month,
            'revenue' => $data[$month] ?? 0  // Nếu không có dữ liệu, đặt doanh thu = 0
        ], $months);
    
        return response()->json($fullData);
    }
    
    public function weeklyRevenueStats()
    {
        $data = DB::table('orders')
            ->select(DB::raw('YEAR(created_at) as year, WEEK(created_at) as week, SUM(total_amount) as revenue'))
            ->groupBy(DB::raw('YEAR(created_at), WEEK(created_at)'))
            ->orderBy('year', 'desc')
            ->orderBy('week', 'desc')
            ->get();
        return response()->json($data);
    }
    public function dailyRevenueStats()
    {
        // Truy vấn doanh thu theo ngày
        $data = DB::table('orders')
            ->select(DB::raw('DATE(created_at) as date, SUM(total_amount) as revenue'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc')
            ->get();

        // Trả về dữ liệu dưới dạng JSON
        return response()->json($data);
    }
    public function topSellingProducts()
    {
        $data = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity')
            )
            ->groupBy('products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        return response()->json($data);
    }
    public function orderStatusStats()
    {
        $data = DB::table('orders')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return response()->json($data);
    }
}
