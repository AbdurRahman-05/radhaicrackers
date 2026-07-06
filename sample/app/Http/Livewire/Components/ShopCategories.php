<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use App\Models\Stock;
use App\Services\SaleProductService;

class ShopCategories extends Component
{
    public $categories = [];

    protected $saleProductService;

    public function boot(SaleProductService $saleProductService)
    {
        $this->saleProductService = $saleProductService;
    }

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $categoryData = [
            'BIJILI CRACKERS' => '⚡',
            'BOMBS' => '💣',
            'CHIT PUT' => '🎆',
            'GIFT BOX' => '🎁',
            'ROCKETS' => '🚀',
            'SINGLE FLASH' => '⚡',
            'SPARKLERS' => '✨',
            'TWINKLING STAR' => '⭐',
        ];

        $this->categories = [];
        
        foreach ($categoryData as $category => $icon) {
            $count = Stock::active()->where('category', $category)->count();
            $this->categories[$category] = [
                'icon' => $icon,
                'count' => $count,
                'url' => route('shop') . '?category=' . urlencode($category)
            ];
        }
    }

    public function render()
    {
        return view('livewire.components.shop-categories');
    }
} 