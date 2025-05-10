<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home'); 
        }
    
        return view('admin.auth.login'); 
    }
    public function login(Request $request)
    {
        try {
            $email = $request->input("email");
            if (!$email)
            {
                return redirect()->with("status", "Vui lòng nhập email!!!");
            }

            $user = User::where("email", $email)->first();
            if (!$user) {
                return back()->withErrors([
                    'email' => 'Tài khoản không tồn tại.'
                ])->withInput();
            }
            if (!Auth::attempt(['email' => $email, 'password' => $request->password], $request->remember)) {
                return back()->withErrors([
                    'email' => 'Email hoặc mật khẩu không đúng.'
                ])->withInput();
            }
    
        // Đăng nhập thành công
        return redirect('/')->with("status", "Đăng nhập thành công");
        } catch (Exception $e)
        {
            return response()->json([
                "message" => "Đã xảy ra lỗi!!!",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function logout()
    {
        Auth::logout(); 
        request()->session()->invalidate();
        request()->session()->invalidate();
        return redirect('admin/login')->with("status", "Đăng xuất thành công"); 
    }
}
