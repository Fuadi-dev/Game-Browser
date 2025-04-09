<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loglogin extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'browser',
        'timezone',
        'login_at',
        'logout_at'
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
    ];

    // Relasi ke user - satu log login dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper untuk menghitung durasi login
    public function getDurationAttribute()
    {
        if (!$this->logout_at) {
            return 'Still Active';
        }

        $duration = $this->login_at->diffInSeconds($this->logout_at);
        $hours = floor($duration / 3600);
        $minutes = floor(($duration % 3600) / 60);
        $seconds = $duration % 60;

        return "{$hours}h {$minutes}m {$seconds}s";
    }
}
