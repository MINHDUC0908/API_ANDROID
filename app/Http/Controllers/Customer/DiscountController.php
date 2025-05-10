<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        try {
            $product_discounts = Product::with(["discount", "images"])
                ->whereHas('discount', function ($query) {
                    $query->where('start_date', '<=', now())
                          ->where('end_date', '>=', now());
                })                
                ->get();
    
            return response()->json([
                "message" => "Tải dữ liệu thành công!!!",
                "data" => $product_discounts
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Lỗi khi tải dữ liệu!!!",
                "error" => $e->getMessage()
            ]);
        }
    }
    
}
