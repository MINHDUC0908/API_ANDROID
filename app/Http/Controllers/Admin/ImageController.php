<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        try {
            $product_id = $request->input('product_id');
            $product = Product::findOrFail($product_id);

            if ($request->hasFile('image_path')) {
                $image_file = $request->file('image_path');
                $filename = time() . "_" . $image_file->getClientOriginalName();
                $path = public_path("products");

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $image_file->move($path, $filename);

                $image = new Image();
                $image->image_path = "products/" . $filename;
                $image->product_id = $product->id;
                $image->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Ảnh đã được tải lên thành công',
                    'data' => $image
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Không có file ảnh được cung cấp'
                ], 422);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Đã xảy ra lỗi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
