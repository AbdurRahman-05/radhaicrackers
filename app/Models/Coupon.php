<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type', // percentage, fixed_amount, bonus_items
        'value', // percentage value, fixed amount, or bonus quantity
        'minimum_order_amount',
        'maximum_discount',
        'usage_limit',
        'used_count',
        'user_limit', // per user usage limit
        'starts_at',
        'expires_at',
        'is_active',
        'applies_to_categories', // JSON array of categories
        'excluded_products', // JSON array of product IDs
        'bonus_product_id', // for bonus item type
        'bonus_quantity', // for bonus item type
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'user_limit' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'applies_to_categories' => 'array',
        'excluded_products' => 'array',
        'bonus_quantity' => 'integer',
    ];

    // Coupon types
    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED_AMOUNT = 'fixed_amount';
    const TYPE_BONUS_ITEMS = 'bonus_items';

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('starts_at')
                          ->orWhere('starts_at', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', now());
                    });
    }

    public function scopeValid($query)
    {
        return $query->active()
                    ->where(function ($q) {
                        $q->whereNull('usage_limit')
                          ->orWhere('usage_limit', '<=', 0) // Unlimited usage
                          ->orWhereRaw('used_count < usage_limit AND usage_limit > 0'); // Valid usage limit
                    });
    }

    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        // Fix: Only check usage limit if it's greater than 0
        if ($this->usage_limit && $this->usage_limit > 0 && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function canBeUsedByUser($userId)
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($this->user_limit) {
            $userUsageCount = CouponUsage::where('coupon_id', $this->id)
                                        ->where('user_id', $userId)
                                        ->count();
            return $userUsageCount < $this->user_limit;
        }

        return true;
    }

    public function calculateDiscount($orderAmount)
    {
        if ($orderAmount < $this->minimum_order_amount) {
            return 0;
        }

        switch ($this->type) {
            case self::TYPE_PERCENTAGE:
                $discount = $orderAmount * ($this->value / 100);
                if ($this->maximum_discount) {
                    $discount = min($discount, $this->maximum_discount);
                }
                return $discount;

            case self::TYPE_FIXED_AMOUNT:
                return min($this->value, $orderAmount);

            case self::TYPE_BONUS_ITEMS:
                return 0; // Bonus items don't reduce order amount

            default:
                return 0;
        }
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function bonusProduct()
    {
        return $this->belongsTo(Stock::class, 'bonus_product_id');
    }
    
    /**
     * Get usage display format (e.g., "1/2" or "5/Unlimited")
     */
    public function getUsageDisplayAttribute()
    {
        if ($this->usage_limit && $this->usage_limit > 0) {
            return "{$this->used_count}/{$this->usage_limit}";
        }
        return "{$this->used_count}/Unlimited";
    }
    
    /**
     * Check if coupon has reached its usage limit
     */
    public function hasReachedUsageLimit()
    {
        return $this->usage_limit && $this->usage_limit > 0 && $this->used_count >= $this->usage_limit;
    }
    
    /**
     * Get status with usage information
     */
    public function getStatusWithUsageAttribute()
    {
        if (!$this->is_active) {
            return 'Inactive';
        }
        
        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'Expired';
        }
        
        if ($this->hasReachedUsageLimit()) {
            return 'Limit Reached';
        }
        
        return 'Active';
    }
} 