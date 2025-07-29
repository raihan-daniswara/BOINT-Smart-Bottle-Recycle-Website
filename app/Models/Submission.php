<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'submission_date',
        'user_id',
        'user_deleted_id',
        'device_id',
        'device_deleted_id',
        'bottle_count',
        'points_earned',
        'status'
    ];

    // Auto-generate submission_code
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($submission) {
            do {
                $code = '#BP' . strtoupper(Str::random(6));
            } while (self::where('submission_code', $code)->exists());

            $submission->submission_code = $code;
        });
    }
    
        // Relationship: Submission belongs to a User
    public function user(){
        return $this->belongsTo(User::class)->withDefault();
    }

    // Relationship: Submission belongs to a Device
    public function device(){
        return $this->belongsTo(Device::class)->withDefault();
    }
}
