<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with(['brand', 'category', 'images', 'colors'])->has("colors")->get();
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
    public function store(Request $request)
    {
        try {
            $product = new Product();
            $product->product_name = $request->input("product_name");
            $product->category_id = $request->input("category_id");
            $product->brand_id = $request->input("brand_id");
            $product->price = $request->input("price");
            $product->description = $request->input("description");
            $product->save();

            // Upload nhiều ảnh
            if ($request->hasFile('image_path')) {
                foreach ($request->file('image_path') as $file) {
                    $filename = time() . "_" . $file->getClientOriginalName();
                    $file->move(public_path("products"), $filename);

                    $image = new Image();
                    $image->image_path = "products/" . $filename; // đúng tên cột trong DB
                    $image->product_id = $product->id;
                    $image->save();
                }
            }

            return response()->json([
                "message" => "Product created successfully",
                "data" => $product->load('images') // trả về kèm ảnh
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error creating product",
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
}
