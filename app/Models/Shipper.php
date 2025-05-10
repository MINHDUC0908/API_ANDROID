<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipper extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_type',
        'vehicle_number',
        'status',
        'latitude',
        'longitude',
        'last_online'
    ];

    protected $casts = [
        'latitude' => 'decimal:10,7',
        'longitude' => 'decimal:10,7',
        'status' => 'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDeliveries()
    {
        return $this->hasMany(OrderDelivery::class);
    }
}
