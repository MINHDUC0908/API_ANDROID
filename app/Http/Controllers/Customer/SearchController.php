<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // Xác thực yêu cầu tìm kiếm từ người dùng
        $request->validate([
            'query' => 'required|string|min:3',  // Đảm bảo tìm kiếm có ít nhất 3 ký tự
        ]);

        // Lấy từ người dùng câu tìm kiếm
        $query = $request->input('query');

        // Gọi OpenAI API để xử lý yêu cầu tìm kiếm
        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',  // Đổi mô hình thành gpt-3.5-turbo
            'messages' => [
                ['role' => 'system', 'content' => 'Bạn là một trợ lý giúp người dùng tìm ra những chiếc điện thoại tốt nhất dựa trên yêu cầu của họ.'],
                ['role' => 'user', 'content' => "Tìm chiếc điện thoại tốt nhất dựa trên yêu cầu sau: \"$query\""]
            ],
            'max_tokens' => 150,  // Giới hạn số token mà phản hồi có thể sử dụng
            'temperature' => 0.7,  // Độ sáng tạo của mô hình trong trả lời
        ]);

        // Lấy gợi ý từ phản hồi của OpenAI
        $suggestion = $response['choices'][0]['message']['content'];

        // Tìm các sản phẩm trong cơ sở dữ liệu phù hợp với yêu cầu
        $products = Product::where('product_name', 'LIKE', "%$query%")
                           ->orWhere('description', 'LIKE', "%$query%")
                           ->get();

        // Trả về kết quả dưới dạng JSON
        return response()->json([
            'suggestion' => $suggestion,
            'products' => $products,
        ]);
    }
}
