<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user)
            {
                return response()->json([
                    "message" => "Người dùng không tồn tại!!!"
                ]);
            }
            $user->name = $request->input("name");
            $user->phone = $request->input("phone");
            $user->gender = $request->input("gender");
            $user->birth_date = $request->input("birth_date");
            $user->save();
            return response()->json([
                "message" => "Cập nhật sản phẩm thành công",
                "data" => $user
            ]);
        } catch (Exception $e)
        {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi update người dùng.',
                'error' =>$e->getMessage(),
            ], 500);
        }
    }

    public function uploadImage(Request $request, $id)
    {
        try {
            $user = User::find($id);
            if (!$user)
            {
                return response()->json([
                    "message" => "Người dùng không tồn tại!!!"
                ]);
            }
            if ($request->hasFile("image"))
            {
                $image = $request->file("image");
                $image_name = time() . " - " . $image->getClientOriginalName();
                $image->move(public_path("imageUser"), $image_name);
                $user->image = $image_name;
                $user->save();
            }
            return response()->json([
                "message" => "Cập nhật sản phẩm thành công",
                "data" => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi update người dùng.',
                'error' =>$e->getMessage(),
            ], 500);
        }
    }
}
