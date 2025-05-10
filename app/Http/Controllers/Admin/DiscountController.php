<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DiscountController extends Controller
{
    public function index()
    {
        try {
            $discounts = Discount::orderBy("created_at", "DESC")->get();
            $products = Product::all();
            return view("admin.discount.index", compact("discounts", "products"));
        } catch (Exception $e)
        {
            return response()->json([
                'message' => "Lỗi khi tải sản phẩm",
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => [
                'required',
                Rule::unique('discounts', 'product_id')
            ],
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ], [
            'product_id.unique' => 'Sản phẩm này đã có giảm giá!',
        ]);
    
        try {
            $discount = new Discount();
            $discount->product_id = $request->product_id;
            $discount->discount_type = $request->discount_type;
            $discount->discount_value = $request->discount_value;
            $discount->start_date = $request->start_date;
            $discount->end_date = $request->end_date;
            $discount->status = $request->has('status') ? 1 : 0;
            $discount->save();
    
            return back()->with("status", "Giảm giá đã được thêm thành công");
        } catch (Exception $e) {
            return back()->with("error", "Lỗi khi thêm giảm giá: " . $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $exists = Discount::where('product_id', $request->product_id)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return back()->with('error', 'Sản phẩm này đã có giảm giá.');
            }
            $discount = Discount::findOrFail($id);
            $discount->product_id = $request->product_id;
            $discount->discount_type = $request->discount_type;
            $discount->discount_value = $request->discount_value;
            $discount->start_date = $request->start_date;
            $discount->end_date = $request->end_date;
            $discount->status = $request->has('status') ? 1 : 0;
            $discount->save();
    
            return back()->with("status", "Giảm giá đã được update thành công");
        } catch (Exception $e) {
            return back()->with("error", "Lỗi khi thêm giảm giá: " . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $discount = Discount::findOrFail($id);
            if ($discount)
            {
                $discount->delete();
                return back()->with("status", "Giảm giá đã được xóa thành công");
            } else {
                return back()->with("error", "Giảm giá sản phẩm không tồn tại");
            }
        } catch (Exception $e)
        {
            return back()->with("error", "Lỗi khi xóa giảm giá sản phẩm");
        }
    }
}
