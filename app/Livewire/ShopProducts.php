<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Stock;

class ShopProducts extends Component
{
    use WithPagination;

    public $selectedProducts = [];
    public $categorySlug = '';

    // Persist selection in query string
    protected $queryString = ['selectedProducts', 'categorySlug'];

    public function mount()
    {
        // Get category slug from URL if present
        $this->categorySlug = request()->query('category', '');
    }

    public function updatingPage()
    {
        // Do nothing, just to prevent reset
    }

    public function render()
    {
        $query = Stock::query()
            ->where('is_active', true)
            ->where('show_on_shop', true)
            ->where('quantity', '>', 0);

        // Filter by category if slug is present
        if ($this->categorySlug) {
            $query->whereHas('category', function($q) {
                $q->where('slug', $this->categorySlug);
            });
        }

        $products = $query->orderBy('category')
            ->orderBy('item_name')
            ->paginate(12);

        return view('livewire.shop-products', [
            'products' => $products,
            'selectedProducts' => $this->selectedProducts,
        ]);
    }
}
