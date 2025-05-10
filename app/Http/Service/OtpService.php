<?php

namespace App\Http\Service;

use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class OtpService 
{
    public function generateOtp($email)
    {
        $otp = rand(100000, 999999);

        // Lưu OTP vào cache 5 phút
        Cache::put("otp_{$email}", $otp, now()->addMinutes(5));

        // Gửi email
        Mail::to($email)->send(new ForgotPassword($otp));

        return $otp;
    }

    public function validateOtp($email, $otp)
    {
        $cachedOtp = Cache::get("otp_{$email}");
        return $cachedOtp && $cachedOtp == $otp;
    }

    public function markOtpVerified($email)
    {
        Cache::put("otp_verified_{$email}", true, now()->addMinutes(10));
    }

    public function isOtpVerified($email)
    {
        return Cache::get("otp_verified_{$email}", false);
    }

    public function clearOtpData($email)
    {
        Cache::forget("otp_{$email}");
        Cache::forget("otp_verified_{$email}");
    }
}
