<?php

namespace App\Services;

use App\Models\Stock;
use Illuminate\Support\Collection;

class SaleProductService
{
    /**
     * Get top sale products for Best Offers section
     */
    public function getTopSaleProducts(int $limit = 5): Collection
    {
        return Stock::active()
            ->whereNotNull('discount_percentage')
            ->orderBy('discount_percentage', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get all sale products
     */
    public function getAllSaleProducts(): Collection
    {
        return Stock::active()
            ->where('quantity', '>', 0)
            ->where(function($query) {
                $query->whereNotNull('discount_percentage')
                      ->orWhereRaw('original_price > price');
            })
            ->orderBy('discount_percentage', 'desc')
            ->get();
    }

    /**
     * Get sale products by category
     */
    public function getSaleProductsByCategory(string $category): Collection
    {
        return Stock::active()
            ->whereNotNull('discount_percentage')
            ->where('category', $category)
            ->orderBy('discount_percentage', 'desc')
            ->get();
    }

    /**
     * Calculate discount percentage
     */
    public function calculateDiscountPercentage(float $originalPrice, float $currentPrice): int
    {
        if ($originalPrice <= 0) {
            return 0;
        }
        
        return (int) round((($originalPrice - $currentPrice) / $originalPrice) * 100);
    }

    /**
     * Get products with highest discounts
     */
    public function getHighestDiscountProducts(int $limit = 10): Collection
    {
        return Stock::active()
            ->whereNotNull('discount_percentage')
            ->where('discount_percentage', '>', 0)
            ->orderBy('discount_percentage', 'desc')
            ->take($limit)
            ->get();
    }
} 