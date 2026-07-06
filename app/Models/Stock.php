<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    public const CATEGORY_ORDER = [
        'BOMBS',
        'BIJILI CRACKERS',
        'ROCKETS',
        'TWINKLING STAR',
        'CHIT PUT',
        'GIFT BOX',
        'SINGLE FLASH',
        'SPARKLERS'
    ];
public function images()
    {
        return $this->hasMany(StockImage::class);
    }

    protected $fillable = [
        'item_name',
        'quantity',
        'price',
        'original_price',
        'discount_percentage',
        'special_discount_percentage',
        'category',
        'category_id',
        'description',
        'image',
        'expires_at',
        'is_active',
        'show_on_shop',
        'last_released_at',
        'next_release_at',
        'youtube_url', //added youtube url
        'ordered_count',
        'is_popular',
        'is_latest',
        'order_within_category',
    ];
    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount_percentage' => 'integer',
        'special_discount_percentage' => 'integer',
        'expires_at' => 'datetime',
        'last_released_at' => 'datetime',
        'next_release_at' => 'datetime',
        'is_active' => 'boolean',
        'show_on_shop' => 'boolean',
        'ordered_count' => 'integer',
        'is_popular' => 'boolean',
        'is_latest' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class);
    }

    public function logAction($action, $details = '', $performedBy = null)
    {
        return $this->stockLogs()->create([
            'action' => $action,
            'details' => $details,
            'quantity_before' => $this->getOriginal('quantity') ?? $this->quantity,
            'quantity_after' => $this->quantity,
            'performed_by' => $performedBy ?? auth()->id(),
        ]);
    }
} 