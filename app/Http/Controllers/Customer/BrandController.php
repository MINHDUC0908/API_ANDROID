<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        try {
            $brands = Brand::orderBy("view", "DESC")->take(6)->get();
            return response()->json([
                'message' => "Hiển thị thương hiệu nổi bật thành công",
                "data" => $brands
            ]);
        } catch(Exception $e)
        {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi lấy danh sách sản phẩm.',
                'error' =>$e->getMessage(),
            ], 500);
        }
    }

    public function getProductWithBrand(Request $request)
    {
        try {
            $brandName = $request->query('brand_name');

            // Lấy danh sách sản phẩm có brand_name tương ứng
            $products = Product::whereHas('brand', function ($query) use ($brandName) {
                $query->where('brand_name', $brandName);
            })->with(['brand', 'images', 'category'])->get();    

            if ($products->isEmpty()) {
                return response()->json([
                    'message' => "Không tìm thấy sản phẩm nào thuộc thương hiệu: $brandName",
                    'data' => []
                ], 404);
            }

            return response()->json([
                'message' => "Hiển thị sản phẩm theo thương hiệu thành công",
                'data' => $products
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi lấy danh sách sản phẩm.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
