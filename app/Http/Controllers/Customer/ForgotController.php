<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Jobs\DeletePasswordReset;
use App\Mail\ForgotPassword;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotController extends Controller
{
    public function sendOTP(Request $request)
    {
        try {
            $email = $request->email;

            // Xóa các token cũ của email
            PasswordReset::where('email', $email)->delete();
            if (!$email)
            {
                return response()->json([
                    "message" => "Vui lòng nhập email!!!"
                ]);
            }

            $user = User::where("email", $email)->first();
            if (!$user)
            {
                return response()->json([
                    "message" => "Không tồn tại người dùng!!!"
                ]);
            }
            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $resetToken = Str::uuid()->toString();
            // Lưu OTP vào bảng password_resets
            $result = PasswordReset::create([
                'email' => $email,
                'token' => Hash::make($otp),
                'reset_token' => $resetToken,
                'created_at' => Carbon::now(),
            ]);

            // Dispatch Job với độ trễ 30 giây


            // Gửi mail
            Mail::to($email)->send(new ForgotPassword($otp));
            return response()->json([
                "success" => true,
                'message' => 'Gửi OTP thành công. Vui lòng kiểm tra email của bạn!',
                'data' => $resetToken,
            ]);
        } catch (Exception $e)
        {
            return response()->json([
                'message' => 'Lỗi khi gửi dữ liệu',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $otp = $request->otp;
            $reset_token = $request->reset_token;

            if (!$otp || !$reset_token) {
                return response()->json([
                    "message" => 'Vui lòng nhập đầy đủ OTP và reset_token!'
                ], 400);
            }

            $passwordReset = PasswordReset::where('reset_token', $reset_token)->latest()->first();

            if (!$passwordReset) {
                return response()->json([
                    "message" => "Không tìm thấy yêu cầu đặt lại mật khẩu nào với reset_token này."
                ], 404);
            }

            // So sánh OTP nhập vào với token đã hash
            if (!Hash::check($otp, $passwordReset->token)) {
                return response()->json([
                    "message" => "OTP không chính xác!"
                ], 401);
            }
            PasswordReset::where('reset_token', $reset_token)->update([
                'is_verified' => true
            ]);            


            // Lấy email từ password_resets rồi truy vấn user
            $user = User::where('email', $passwordReset->email)->first();

            if (!$user) {
                return response()->json([
                    "message" => "Không tìm thấy người dùng tương ứng!"
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Xác thực OTP thành công.",
                "data" => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Lỗi khi xác nhận!",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $reset_token = $request->reset_token;
            $new_password = $request->new_password;

            if (!$reset_token || !$new_password) {
                return response()->json([
                    "message" => "Vui lòng cung cấp đầy đủ reset_token và mật khẩu mới!"
                ], 400);
            }

            // Tìm token
            $passwordReset = PasswordReset::where('reset_token', $reset_token)->latest()->first();

            if (!$passwordReset) {
                return response()->json([
                    "message" => "Reset token không hợp lệ hoặc đã hết hạn!"
                ], 404);
            }

            // Tìm user
            $user = User::where('email', $passwordReset->email)->first();

            if (!$user) {
                return response()->json([
                    "message" => "Không tìm thấy người dùng tương ứng!"
                ], 404);
            }

            // Cập nhật mật khẩu
            $user->password = Hash::make($new_password);
            $user->save();

            // Xoá các bản ghi đặt lại mật khẩu cũ
            PasswordReset::where('email', $user->email)->delete();

            return response()->json([
                "success" => true,
                "message" => "Đặt lại mật khẩu thành công!"
            ]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Lỗi khi đặt lại mật khẩu!",
                "error" => $e->getMessage()
            ], 500);
        }
    }


    public function changePassword(Request $request)
    {   
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ]);
            $user_id = Auth::id();
            if (!$user_id)
            {
                return response()->json([
                    "message" => "Vui lòng đăng nhập để sử dụng tài nguyên!!!"
                ], 401);
            }
            $user = User::where("id", $user_id)->first();
            if (!$user)
            {
                return response()->json([
                    "message" => "Người dùng không tồn tại!!!"
                ], 404);
            }
            if (!$request->current_password)
            {
                return response()->json([
                    'message' => 'Vui lòng nhập mật khẩu hiện tại!!!'
                ], 400);
            }
            if (!Hash::check($request->current_password, $user->password))
            {
                return response()->json([
                    'message' => 'Mật khẩu không chính xác'
                ], 400);
            }
            if (!$request->new_password)
            {
                return response()->json([
                    'message' => 'Vui lòng nhập mật khẩu mới!!!'
                ], 400);
            }
            $user->password = Hash::make($request->new_password);
            $user->save();
            // Trả về phản hồi thành công
            return response()->json([
                'message' => 'Mật khẩu đã được thay đổi thành công!',
            ], 200);
        } catch (Exception $e)
        {
            return response()->json([
                'message' => 'Lỗi khi cập nhật dữ liệu',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
