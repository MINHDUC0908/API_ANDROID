<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('id', 'desc')->get();
        return view('admin.coupon.index', compact('coupons'));
    }
    public function create()
    {
        return view('admin.coupon.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code|max:20',
            'discount_amount' => 'required|numeric|min:1',
            'expires_at' => 'required|date|after:today',
        ]);

        Coupon::create([
            'code' => strtoupper($request->code),
            'discount_amount' => $request->discount_amount,
            'expires_at' => Carbon::parse($request->expires_at),
            'quantity' => $request->input('quantity')
        ]);

        return redirect()->route('coupon.index')->with('status', 'Mã giảm giá đã được thêm thành công!');
    }
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view("admin.coupon.edit", compact("coupon"));
    }
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code' => [
                'required', 'string', 'max:20',
                Rule::unique('coupons')->ignore($coupon->id), // Không kiểm tra trùng nếu không sửa code
            ],
            'discount_amount' => 'required|numeric|min:1',
            'expires_at' => 'required|date|after:today',
        ]);

        $coupon->update([
            'code' => strtoupper($request->code),
            'discount_amount' => $request->discount_amount,
            'expires_at' => Carbon::parse($request->expires_at),
            'quantity' => $request->input('quantity')
        ]);

        return redirect()->route('coupon.index')->with('status', 'Mã giảm giá đã được cập nhật thành công!');
    }
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->route("coupon.index")->with("status", "Xóa mã giảm giá thành công");
    }
}
