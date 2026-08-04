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

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return asset('images/firework-default.png');
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        $cleanPath = ltrim($this->image, '/');

        // Strip duplicate storage or public prefixes
        if (str_starts_with($cleanPath, 'public/storage/')) {
            $cleanPath = substr($cleanPath, 15);
        } elseif (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        } elseif (str_starts_with($cleanPath, 'public/')) {
            $cleanPath = substr($cleanPath, 7);
        }

        return asset('storage/' . $cleanPath);
    }

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

    /**
     * Recalculate and update the ordered_count for all stocks or a specific stock
     * based on confirmed (non-cancelled, non-pending) orders.
     */
    public static function recalculateOrderedCounts($stockId = null)
    {
        $orders = Order::whereNotIn('status', ['cancelled', 'pending'])->get(['items_json']);

        $countsMap = [];
        foreach ($orders as $ord) {
            $items = is_array($ord->items_json) ? $ord->items_json : json_decode($ord->items_json ?? '[]', true);
            if (is_array($items)) {
                foreach ($items as $item) {
                    $pId = $item['product_id'] ?? $item['stock_id'] ?? null;
                    $qty = (int)($item['quantity'] ?? 0);
                    if ($pId && $qty > 0) {
                        $countsMap[$pId] = ($countsMap[$pId] ?? 0) + $qty;
                    }
                }
            }
        }

        if ($stockId) {
            $newCount = $countsMap[$stockId] ?? 0;
            static::where('id', $stockId)->update(['ordered_count' => $newCount]);
        } else {
            $allStocks = static::all();
            foreach ($allStocks as $stock) {
                $newCount = $countsMap[$stock->id] ?? 0;
                if ($stock->ordered_count != $newCount) {
                    $stock->update(['ordered_count' => $newCount]);
                }
            }
        }
    }
} 