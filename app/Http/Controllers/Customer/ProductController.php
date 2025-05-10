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
            $topViewedProducts = Product::orderBy('view_count', 'desc')->with(['images', "discount"])
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

    public function index(Request $request)
    {
        try {
            $products = Product::with(['brand', 'category', 'images', 'colors'])->withAvg('rating', 'rating')
                ->has('colors')
                ->get(); // lấy tất cả sản phẩm
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

    public function filter(Request $request)
    {
        try {
            $sortType = $request->query('sortType'); // nhận sortType từ client
            $priceRange = $request->query("priceRange");
            $products = Product::with(['brand', 'category', 'images', 'colors', "discount"])->withAvg('rating', 'rating')
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
                    $products = Product::withAvg('rating', 'rating')->with(['brand', 'category', 'images', 'colors'])
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


    public function show($id)
    {
        try {
            $product = Product::with(['brand', 'category', 'images.color', "colors"])->find($id);
            $product->increment('view_count');
            if (!$product) {
                return response()->json([
                    "message" => "Product not found"
                ], 404);
            }
            return response()->json([
                "message" => "Product details",
                "data" => $product
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error fetching product",
                "error" => $e->getMessage()
            ], 500);
        }
    }


    public function search(Request $request)
    {
        try {
            $keyword = $request->query('keyword');
            $products = Product::with(['brand', 'category', 'images', 'colors'])->withAvg('rating', 'rating')
                ->where('product_name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('description', 'LIKE', '%' . $keyword . '%')
                ->get();
            return response()->json([
                "message" => "List of products",
                "data" => $products
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error searching products",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
