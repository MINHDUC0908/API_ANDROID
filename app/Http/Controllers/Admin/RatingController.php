<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::orderBy("created_at", "DESC")->with(["product.images"])->get();
        return view('admin.rating.index', compact('ratings'));
    }
    public function ratingImage(Request $request)
    {
        // Lấy dữ liệu từ request
        $filter = $request->query('filter', 'default');
        $rating = $request->query('rating', 'default');
    
        // Khởi tạo truy vấn với Eager Loading để giảm số lượng query
        $query = Rating::with(['user', 'product']);
    
        // Kiểm tra và thêm điều kiện lọc theo hình ảnh
        if ($filter !== 'default') {
            if ($filter === 'haveImage') {
                $query->whereNotNull('image');  // Lọc có hình ảnh
            } elseif ($filter === 'noImage') {
                $query->whereNull('image');  // Lọc không có hình ảnh
            }
        }
    
        // Kiểm tra và thêm điều kiện lọc theo số sao
        if ($rating !== 'default') {
            $query->where('rating', $rating);
        }

        // Lấy dữ liệu đã lọc
        $ratings = $query->get();
        return response()->json(['data' => $ratings]);
    }
    
    public function destroy($id)
    {
        $rating = Rating::find($id);
        if ($rating) {
            $rating->delete();
            return response()->json(['success' => true, "status" => "Xóa đánh giá thành công"]);
        }
        return response()->json(['success' => false, 'message' => 'Không tìm thấy đánh giá']);
    }    
}
