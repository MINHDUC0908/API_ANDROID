<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_amount',
        'expires_at',
        'quantity'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // Kiểm tra mã giảm giá có hợp lệ không
    public function isValid()
    {
        // return !$this->is_used && Carbon::now()->lessThan($this->expires_at);
        return Carbon::now()->lessThan($this->expires_at);
    }

    // Mối quan hệ với Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
