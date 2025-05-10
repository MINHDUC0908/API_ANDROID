<?php

namespace App\Events;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductAdded
{
    use Dispatchable, SerializesModels;

    public $user;
    public $product;
    public $action;

    // Action có thể là 'thêm mới', 'xóa', 'cập nhật', ...
    public function __construct(User $user, Product $product, string $action)
    {
        $this->user = $user;
        $this->product = $product;
        $this->action = $action;
    }
}
