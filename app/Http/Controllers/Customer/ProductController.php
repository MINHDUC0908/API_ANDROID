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
            $sortType = $request->query('sortType'); // nhận sortType từ client
            $priceRange = $request->query("priceRange");
            $products = Product::with(['brand', 'category', 'images', 'colors'])
                ->has('colors'); 


            // Áp dụng lọc theo khoảng giá nếu có
            if ($priceRange) {
                // Giả sử priceRange là chuỗi "minPrice-maxPrice" (ví dụ: "100-500")
                list($minPrice, $maxPrice) = explode('-', $priceRange);
                $products->whereBetween('price', [$minPrice, $maxPrice]);
            }


            // Áp dụng sortType
            switch ($sortType) {
                case 'price_asc':
                    $products->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $products->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $products->orderBy('product_name', 'asc');
                    break;
                case 'name_desc':
                    $products->orderBy('product_name', 'desc');
                    break;
                case 'rating_desc':
                    $products = Product::withAvg('rating', 'rating')
                    ->orderByDesc('rating_avg_rating');
                    break;
                default:
                    $products->orderBy('id', 'desc');
                    break;
            }
            
            $products = $products->get(); // thực thi query
            
            return response()->json([
                "message" => "List of products",
                "data" => $products
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error fetching products",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
