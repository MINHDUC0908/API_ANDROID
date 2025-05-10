<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PasswordReset;
use Carbon\Carbon;

class ClearExpiredOtps extends Command
{
    protected $signature = 'otp:clear-expired';

    protected $description = 'Xóa các OTP đã hết hạn (sau 30 giây)';

    public function handle()
    {
        $count = PasswordReset::where('created_at', '<', Carbon::now()->subSeconds(30))->delete();

        $this->info("Đã xóa $count OTP hết hạn.");
    }
}
