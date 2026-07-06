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
        // Get categories from database with their sort order
        $activeCategories = \App\Models\Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $this->categories = [];
        
        foreach ($activeCategories as $category) {
            $count = Stock::active()
                ->where('category', $category->name)
                ->where('show_on_shop', true)
                ->count();
                
            // Only show categories that have products
            if ($count > 0) {
                $icon = $category->icon ?? $this->getDefaultIcon($category->name);
                $this->categories[$category->name] = [
                    'icon' => $icon,
                    'count' => $count,
                    'url' => route('shop', ['category' => $category->slug]),
                    'sort_order' => $category->sort_order
                ];
            }
        }

        // Sort categories by sort_order and then by name
        uasort($this->categories, function($a, $b) {
            if ($a['sort_order'] === $b['sort_order']) {
                return strcmp(array_search(array_search($a, $this->categories), array_keys($this->categories)),
                            array_search(array_search($b, $this->categories), array_keys($this->categories)));
            }
            return $a['sort_order'] <=> $b['sort_order'];
        });
    }

    private function getDefaultIcon($categoryName)
    {
        $defaultIcons = [
            'BIJILI CRACKERS' => '⚡',
            'BOMBS' => '💣',
            'CHIT PUT' => '🎆',
            'GIFT BOX' => '🎁',
            'ROCKETS' => '🚀',
            'SINGLE FLASH' => '⚡',
            'SPARKLERS' => '✨',
            'TWINKLING STAR' => '⭐',
        ];

        return $defaultIcons[$categoryName] ?? '🎆';
    }

    public function render()
    {
        return view('livewire.components.shop-categories');
    }
} 