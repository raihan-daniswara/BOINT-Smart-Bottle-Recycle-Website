<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOtpAttempt extends Model
{
    protected $fillable = [
        'email', 
        'otp', 
        'submitted_at'
    ];
}
