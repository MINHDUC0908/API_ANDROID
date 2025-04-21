<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ShiipingController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user_id = Auth::id();
            if (!$user_id) {
                return response()->json(['message' => 'Người dùng chưa đăng nhập'], 401);
            }
            // Xác thực dữ liệu đầu vào
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|regex:/^[0-9]{10,11}$/',
                'province' => 'required|max:255',
                'district' => 'required|max:255',
                'ward' => 'required|max:255',
                'address' => 'required|string|max:500',
            ]);
            // Lưu thông tin vào bảng shipping_addresses
            $shippingAddress = ShippingAddress::create([
                'user_id' => $user_id,
                'name' => $validatedData['name'],
                'phone' => $validatedData['phone'],
                'province' => $validatedData['province'],
                'district' => $validatedData['district'],
                'ward' => $validatedData['ward'],
                'address' => $validatedData['address'],
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Địa chỉ giao hàng đã được lưu thành công!',
                'data' => $shippingAddress
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Đã xảy ra lỗi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        try {
            $user_id = Auth::id();
            if (!$user_id) {
                return response()->json(['message' => 'Người dùng chưa đăng nhập'], 401);
            }
            $shippingAddresses = ShippingAddress::where('user_id', $user_id)->get();
            return response()->json([
                'status' => true,
                'data' => $shippingAddresses
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Đã xảy ra lỗi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
