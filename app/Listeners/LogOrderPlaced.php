<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\RecentActivity;

class LogOrderPlaced
{
    public function handle(OrderPlaced $event)
    {
        RecentActivity::create([
            'user_id' => $event->user->id,
            'activity_type' => 'order',
            'activity_data' => [
                'action'     => 'đặt hàng',
                'order_id'   => $event->order->id,
                'order_code' => $event->order->order_number, // ví dụ: mã đơn hàng
            ],
        ]);
    }
}
