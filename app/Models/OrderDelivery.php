<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'shipper_id',
        'delivery_status',
        'delivery_notes',
        'proof_of_delivery',
        'customer_signature'
    ];

    protected $casts = [
        'delivery_status' => 'string'
    ];

    protected $dates = [
        'accepted_at',
        'picked_up_at',
        'delivered_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function shipper()
    {
        return $this->belongsTo(Shipper::class);
    }

    public function deliveryStatusHistory()
    {
        return $this->hasMany(DeliveryStatusHistory::class);
    }
}
