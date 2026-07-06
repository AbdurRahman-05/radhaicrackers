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
        // Discount breakdown fields
        'subtotal',
        'discount_70_percent',
        'amount_after_70_discount',
        'special_discount_15_percent',
        'amount_after_15_discount',
        'packing_charge_5_percent',
        'coupon_discount',
        'final_amount',
        'final_amount_after_coupon',
        'has_gst',
        'gst_amount',
        'transport_provider',
        'transport_details',
        'delivery_type',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'receive_amount' => 'decimal:2',
        'items_json' => 'array',
        // Discount breakdown fields
        'subtotal' => 'decimal:2',
        'discount_70_percent' => 'decimal:2',
        'amount_after_70_discount' => 'decimal:2',
        'special_discount_15_percent' => 'decimal:2',
        'amount_after_15_discount' => 'decimal:2',
        'packing_charge_5_percent' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'final_amount_after_coupon' => 'decimal:2',
        'has_gst' => 'boolean',
        'gst_amount' => 'decimal:2',
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