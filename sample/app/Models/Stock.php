<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'quantity',
        'price',
        'original_price',
        'discount_percentage',
        'category',
        'description',
        'image',
        'expires_at',
        'is_active',
        'show_on_shop',
        'last_released_at',
        'next_release_at',
        'youtube_url', //added youtube url
    ];
    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount_percentage' => 'integer',
        'expires_at' => 'datetime',
        'last_released_at' => 'datetime',
        'next_release_at' => 'datetime',
        'is_active' => 'boolean',
        'show_on_shop' => 'boolean',
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