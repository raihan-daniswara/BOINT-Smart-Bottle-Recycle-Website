<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = [
        'name',
        'stock',
        'description',
        'points',
        'reward_photo',
        'categories',
        'status',
    ];

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('stock', '>', 0);
    }

    public function isRedeemable()
    {
        return $this->status === 'available' && $this->stock > 0;
    }
}
