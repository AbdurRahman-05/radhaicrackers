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
        'receive_amount',
        'status',
        'payment_status',
        'notes',
        'customer_name',
        'customer_mobile',
        'customer_email',
        'customer_state',
        'customer_district',
        'customer_city',
        'delivery_point',
        'pin_code',
        'coupon_code',
        'verify_code',
        'items_json',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'receive_amount' => 'decimal:2',
        'items_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
  public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function getTotalAmountAttribute($value)
    {
        return $value ?? $this->total;
    }

    // Helper method to get items from JSON
    public function getItemsAttribute()
    {
        return $this->items_json ?? [];
    }
} 