<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'otp',
        'sent_at',
        'expires_at',
        'channel',
        'status',
    ];

    protected $dates = [
        'sent_at',
        'expires_at',
    ];
} 