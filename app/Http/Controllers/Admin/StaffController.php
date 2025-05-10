<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffProfile;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        try {   
            $users = User::where("role", "staff")->orderBy("id", "DESC")->get();
            return view("admin.user.index", compact("users"));
        } catch (Exception $e)
        {
            return response()->json([
                "message" => "Đã xảy ra lỗi!!!",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function create()
    {
        return view("admin.user.create");
    }

    public function store(Request $request)
    {
        try {   
            $user = new User();
            $user->name = $request->input("name");
            $user->email = $request->input("email");
            $user->password = $request->input("password");
            $user->role = "staff";
            $user->save();

            $staff_profile = new StaffProfile();
            $staff_profile->user_id = $user->id;
            $staff_profile->salary = $request->input("salary");
            $staff_profile->status = $request->input("status");
            $staff_profile->start_date = $request->input("start_date");
            $staff_profile->position = $request->input("position");
            $staff_profile->department = $request->input("department");
            $staff_profile->save();
            return redirect()->route("staff.index")->with("status", "Thêm Nhân sự thành công!!!");
        } catch (Exception $e)
        {
            return response()->json([
                "message" => "Đã xảy ra lỗi!!!",
                "error" => $e->getMessage()
            ]);
        }
    }
}
