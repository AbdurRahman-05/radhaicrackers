<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'total_amount',
        'status',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function getTotalAmountAttribute($value)
    {
        return $value ?? $this->total;
    }
} 