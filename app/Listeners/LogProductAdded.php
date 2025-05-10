<?php

namespace App\Listeners;

use App\Events\ProductAdded;
use App\Models\RecentActivity;

class LogProductAdded
{
    public function handle(ProductAdded $event)
    {
// Tạo hoạt động cho sản phẩm
        RecentActivity::create([
            'user_id' => $event->user->id,
            'activity_type' => 'product', // Loại hoạt động là sản phẩm
            'activity_data' => [
                'action' => $event->action, // 'thêm', 'xóa', 'cập nhật'...
                'product_id' => $event->product->id,
                'product_name' => $event->product->product_name,
            ],
        ]);
    }
}

