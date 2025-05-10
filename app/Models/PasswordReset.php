<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_resets';

    protected $fillable = [
        'email',
        'token',
        'created_at',
        'reset_token',
        'is_verified'
    ];

    public $timestamps = false;

    public function user()
    {
        $this->hasOne(User::class);
    }
}
