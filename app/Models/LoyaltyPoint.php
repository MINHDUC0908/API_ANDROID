<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    protected $fillable = ['user_id', 'total_points', 'lifetime_points'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor để tính rank theo điểm tích lũy
    public function getRankAttribute()
    {
        $points = $this->lifetime_points;

        return match (true) {
            $points >= 100000 => 'diamond',
            $points >= 50000 => 'gold',
            $points >= 10000 => 'silver',
            default => 'normal',
        };
    }
}
