<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redeem extends Model
{
    protected $fillable = [
        'user_id', 
        'reward_id', 
        'points_used', 
        'voucher_code',
        'status', 
        'redeemed_at', 
        'expires_at',
    ];

     public function user() {
        return $this->belongsTo(User::class);
    }

    public function reward() {
        return $this->belongsTo(Reward::class);
    }
}
