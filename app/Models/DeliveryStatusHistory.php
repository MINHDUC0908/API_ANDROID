<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_delivery_id',
        'status',
        'notes',
        'image',
        'updated_by'
    ];

    public function orderDelivery()
    {
        return $this->belongsTo(OrderDelivery::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
