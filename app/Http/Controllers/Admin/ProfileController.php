<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('admin.profile.index', compact('user'));
    }
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->name = $request->input('name');
            $user->phone = $request->input("phone");
            $user->address = $request->input('address');
            $user->gender = $request->input("gender");
            $user->save();
            return redirect()->route("profile.index")->with("status", "Cập nhật thành công!");
        } catch (Exception $e)
        {
            return redirect()->back()->with("error", "Có lỗi xảy ra!");
        }
    }
    public function image(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
                ]);
                $file = $request->file('image');
                // if (!empty($user->image)) {
                //     Storage::delete('public/profile_images/' . $user->image);
                // }
                // $filename = time() . '.' . $file->getClientOriginalExtension();
                // $file->storeAs('public/profile_images', $filename);
                if ($user->image) {
                    $imagePath = public_path('imageUser/' . $user->image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                $image = $request->file('image');
                $filename = time() . " - " . $image->getClientOriginalName();
                $image->move(public_path("imageUser"), $filename);
                $user->image = $filename;
                $user->save();
            }
            return redirect()->route("profile.index")->with("status", "Cập nhật ảnh đại diện thành công!");
        } catch (Exception $e)
        {
            return redirect()->back()->with("error", "Có lỗi xảy ra!");
        }
    }
    public function deleteImage($id)
    {
        try {
            $user = User::findOrFail($id);
            if (!empty($user->image)) {
                Storage::delete('public/profile_images/' . $user->image);
                $user->image = null;
                $user->save();
            }
            return redirect()->route("profile.index")->with("status", "Xóa ảnh đại diện thành công!");
        } catch (Exception $e)
        {
            Log::debug($e->getMessage());
        }
    }
    public function updatePassword(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            if (!Hash::check($request->current_password, $user->password))
            {
                return back()->with('error', 'Mật khẩu hiện tại không đúng!');
            }
            $user->password = Hash::make($request->password);
            $user->save();
            return back()->with("status", "Cập nhật mật khẩu thành công!!!");
        } catch (Exception $e)
        {
            Log::debug($e->getMessage());
        }
    }
}
