<?php

namespace App\Http\Controllers\Admin;

use App\Events\ProductAdded;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Image;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $name = Auth::user()->name;
            $products = Product::join('categories', 'categories.id', '=', 'products.category_id')
                        ->join('brands', 'brands.id', '=', 'products.brand_id')
                        ->orderBy('products.updated_at', 'DESC')
                        ->select('products.*', 'categories.category_name', 'brands.brand_name')
                        ->with("images")
                        ->paginate(15);
            return view('admin.product.list', compact('products', 'name'));

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error fetching products: ' . $e->getMessage());
        }
    }

    public function create()
    {   
        $categories = Category::all();
        $brands = Brand::all();
        $name = Auth::user()->name;
        return view('admin.product.create', compact('categories', 'brands', 'name'));
    }

    public function store(Request $request)
    {
        try {
            // Lưu sản phẩm
            $product = new Product();
            $product->product_name = $request->input('product_name');
            $product->category_id = $request->input('category_id');
            $product->brand_id = $request->input('brand_id');
            $product->price = $request->input('price');
            $product->description = $request->input('description');
            $product->save();

            // Lưu các màu
            $colorNames = $request->input('name', []);
            $colorCodes = $request->input('code', []);
            $colorQuantities = $request->input('quantity', []);
            $colorImages = $request->file('color_image_path', []);

            for ($i = 0; $i < count($colorNames); $i++) {
                if (!empty($colorNames[$i]) && !empty($colorCodes[$i])) {
                    // Lưu thông tin màu
                    $color = new Color();
                    $color->name = $colorNames[$i];
                    $color->code = $colorCodes[$i];
                    $color->quantity = $colorQuantities[$i] ?? 0;
                    $color->product_id = $product->id;
                    $color->save();

                    // Lưu ảnh của màu
                    if (!empty($colorImages[$i]) && is_array($colorImages[$i])) {
                        foreach ($colorImages[$i] as $img) {
                            if ($img->isValid()) {
                                $filename = time() . '_' . $img->getClientOriginalName();
                                $img->move(public_path('products'), $filename);

                                $image = new Image();
                                $image->image_path = 'products/' . $filename;
                                $image->product_id = $product->id;
                                $image->color_id = $color->id;
                                $image->save();
                            }
                        }
                    }
                }
            }

            // Lưu ảnh sản phẩm chung
            if ($request->hasFile('image_path')) {
                foreach ($request->file('image_path') as $file) {
                    if ($file->isValid()) {
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $file->move(public_path('products'), $filename);

                        $image = new Image();
                        $image->image_path = 'products/' . $filename;
                        $image->product_id = $product->id;
                        $image->color_id = null;
                        $image->save();
                    }
                }
            }
            event(new ProductAdded(Auth::user(), $product, 'thêm mới'));

            return redirect()->route('product.list')->with('status', 'Tạo sản phẩm thành công');
        } catch (Exception $e) {
            Log::error('Lỗi khi tạo sản phẩm: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi tạo sản phẩm: ' . $e->getMessage());
        }
    }



    public function show($id)
    {
        try {
            $product = Product::with(['brand', 'category', 'images.color', "colors"])->find($id);

            if (!$product) {
                return redirect()->back()->with('error', 'Product not found');
            }

            $product->increment('view_count');

            return view('admin.product.show', compact('product'));

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error fetching product: ' . $e->getMessage());
        }
    }

    public function getBrandsByCategory(Request $request)
    {
        $brands = Brand::where('category_id', $request->category_id)->get();
        return response()->json($brands);
    }
}