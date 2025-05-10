<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentActivity extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'activity_type', 'activity_data'];

    protected $casts = [
        'activity_data' => 'array', // Chuyển đổi 'activity_data' thành mảng
    ];

    // Mối quan hệ với bảng users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}