<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Rating;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $product_id = $request->input("product_id");
            if (!$product_id)
            {
                return response()->json([
                    "message" => "Không tìm thấy sản phẩm!!!"
                ]);
            }
            $ratings = Rating::with('user') // lấy luôn user
                ->where('product_id', $product_id)
                ->orderby("id", "DESC")->get();
            return response()->json([
                "message" => "Tải dữ liệu thành công!!!",
                "data" => $ratings
            ]);
        } catch (Exception $e)
        {
            return response()->json([
                "message" => "Lỗi khi tải dữ liệu!!!",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::id();
            if (!$user) {
                return response()->json([
                    "message" => "Vui lòng đăng nhập để sử dụng tài nguyên!!!",
                ], 401); // Unauthorized
            }
    
            $product_id = $request->input("product_id");
            if ($product_id) {
                $product = Product::where("id", $product_id)->first();
                if (!$product) {
                    return response()->json([
                        "message" => "Sản phẩm không tồn tại!!!"
                    ], 404); // Not Found
                }
            } else {
                return response()->json([
                    "message" => "Vui lòng nhập product_id"
                ], 400); // Bad Request
            }
    
            // Kiểm tra xem người dùng đã đánh giá sản phẩm này chưa
            $existingRating = Rating::where("user_id", $user)
                ->where("product_id", $product_id)
                ->first();
            if ($existingRating) {
                return response()->json([
                    "message" => "Bạn đã đánh giá sản phẩm này rồi!!!"
                ], 400); // Bad Request - thêm mã lỗi 400 ở đây
            }
    
            // Kiểm tra xem người dùng đã mua sản phẩm chưa 
            $purchased = Order::where("user_id", $user)
                ->whereHas('orderItems', function($query) use ($product_id) {
                    $query->where("product_id", $product_id);
                })
                ->exists();
    
            if (!$purchased) {
                return response()->json([
                    "message" => "Bạn cần phải mua sản phẩm trước khi đánh giá!!!"
                ], 403); // Forbidden
            }
            
            $rating = new Rating();
            $rating->user_id = $user;
            $rating->product_id = $product_id;
            $rating->rating = $request->input("rating");
    
            if ($request->hasFile("image")) {
                $image = $request->file("image");
                $image_name = time() . '-' . $image->getClientOriginalName();
                $image->move(public_path("rating"), $image_name);
                $rating->image = "rating/" . $image_name;
            }
            $rating->comment = $request->input("comment");
            $rating->save();
    
            return response()->json([
                "message" => "Thêm đánh giá thành công!!!",
                "data" => $rating
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Lỗi khi thêm dữ liệu!!!",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
