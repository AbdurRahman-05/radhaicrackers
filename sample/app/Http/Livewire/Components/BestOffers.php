<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use App\Models\Stock;

class BestOffers extends Component
{
    public $offers = [];

    public function mount()
    {
        $this->loadOffers();
    }

    public function loadOffers()
    {
        $this->offers = Stock::active()
            ->where('discount_percentage', '>', 0)
            ->orderBy('discount_percentage', 'desc')
            ->take(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->item_name,
                    'category' => $product->category,
                    'original_price' => $product->original_price,
                    'price' => $product->price,
                    'discount' => $product->discount_percentage,
                    'description' => $product->description,
                    'icon' => $this->getCategoryIcon($product->category),
                ];
            });
    }

    private function getCategoryIcon($category)
    {
        return match($category) {
            'BOMBS' => '💣',
            'SINGLE FLASH' => '⚡',
            'ROCKETS' => '🚀',
            'SPARKLERS' => '✨',
            'CHIT PUT' => '🎆',
            'TWINKLING STAR' => '⭐',
            'GIFT BOX' => '🎁',
            'BIJILI CRACKERS' => '⚡',
            default => '🎆',
        };
    }

    public function render()
    {
        return view('livewire.components.best-offers');
    }
} 