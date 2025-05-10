<?php

// app/Models/StaffProfile.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffProfile extends Model
{
    use HasFactory;

    // Chỉ định bảng tương ứng với model này
    protected $table = 'staff_profiles';

    // Các trường có thể mass-assignable
    protected $fillable = [
        'user_id',
        'salary',
        'status',
        'position',
        'department',
        'start_date',
        'end_date',
    ];

    // Quan hệ với bảng users (mỗi staff_profile chỉ thuộc về một user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

