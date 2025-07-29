<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'name',
        'location',
        'capacity',
        'max_capacity',
        'status',
        'last_active',
        'current_token',
        'token_expires_at',
        'paired_user_id',
    ];

    // Relationship: Device has many Submissions
    public function submissions(){
        return $this->hasMany(Submission::class);
    }
}
