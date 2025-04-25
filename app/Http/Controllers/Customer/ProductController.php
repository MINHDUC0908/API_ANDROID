<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function incrementProduct()
    {
        try {
            $topViewedProducts = Product::orderBy('view_count', 'desc')->with('images')
                                ->take(6)
                                ->get();
            return response()->json([
                'success' => true,
                'data' => $topViewedProducts
            ], 200);
        } catch (Exception $e)
        {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi lấy danh sách sản phẩm.',
                'error' =>$e->getMessage(),
            ], 500);
        }
    }

    public function filter(Request $request)
    {
        try {
            $price = $request->input("price");
            
        } catch (Exception $e) {
            return response()->json([
                "message" => "Lỗi khi tải dữ liệu!!!",
                "error" => $e->getMessage(),
            ]);
        }
    }
}
