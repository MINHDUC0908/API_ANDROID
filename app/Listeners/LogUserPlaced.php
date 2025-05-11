<?php

namespace App\Listeners;

use App\Events\UserAdded;
use App\Models\RecentActivity;

class LogUserPlaced
{
    public function handle(UserAdded $event)
    {
        RecentActivity::create([
            'user_id' => $event->user->id,
            'activity_type' => 'user',
            'activity_data' => [
                'action'     => 'Đăng ký tài khoản',
                'user_id'   => $event->user->id,
                'name' => $event->user->name,
            ],
        ]);
    }
}
