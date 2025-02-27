<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'profile_image', 'status', 'otp', 'otp_expires_at',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
