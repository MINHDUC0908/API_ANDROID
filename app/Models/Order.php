<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'shipping_address_id',
        'total_amount',
        'payment_method',
        'status',
        "order_number",
        "payment_status",
        "coupon_id",
        "discount_amount"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
    
    public function orderDeliveries()
    {
        return $this->hasMany(OrderDelivery::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }    
}
