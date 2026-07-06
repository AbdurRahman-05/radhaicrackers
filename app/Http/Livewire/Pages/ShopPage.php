<?php

namespace App\Http\Livewire\Pages;

use App\Models\Stock;
use Livewire\Component;
use Livewire\WithPagination;

class ShopPage extends Component
{
    use WithPagination;

    public static function layout()
    {
        return 'components.layouts.app';
    }

    public $search = '';
    public $category_filter = '';
    public $price_filter = '';
    public $sort_by = 'created_at';
    public $sort_direction = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'category_filter' => ['except' => ''],
        'price_filter' => ['except' => ''],
        'sort_by' => ['except' => 'created_at'],
        'sort_direction' => ['except' => 'desc'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedPriceFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sort_by === $field) {
            $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort_by = $field;
            $this->sort_direction = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'category_filter', 'price_filter']);
        $this->resetPage();
    }

    public function addToCart($stockId)
    {
        $stock = Stock::find($stockId);
        if (!$stock || !$stock->is_active || $stock->quantity <= 0) {
            session()->flash('error', 'Product not available.');
            return;
        }

        $cart = session('cart', []);
        $cart[$stockId] = ($cart[$stockId] ?? 0) + 1;
        session(['cart' => $cart]);

        $this->dispatch('cart-updated');
        session()->flash('success', $stock->item_name . ' added to cart!');
    }

    public function getCategoryIcon($category)
    {
        return match($category) {
            'BIJILI CRACKERS' => '⚡',
            'BOMBS' => '💣',
            'CHIT PUT' => '🎆',
            'GIFT BOX' => '🎁',
            'ROCKETS' => '🚀',
            'SINGLE FLASH' => '⚡',
            'SPARKLERS' => '✨',
            'TWINKLING STAR' => '⭐',
            default => '🎆',
        };
    }

    public function render()
    {
        $query = Stock::active()->where('quantity', '>', 0);

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('item_name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('category', 'like', '%' . $this->search . '%');
            });
        }

        // Apply category filter
        if ($this->category_filter) {
            $query->where('category', $this->category_filter);
        }

        // Apply price filter
        if ($this->price_filter) {
            switch ($this->price_filter) {
                case '0-90':
                    $query->where('price', '<=', 90);
                    break;
                case '180-270':
                    $query->whereBetween('price', [180, 270]);
                    break;
                case '360-450':
                    $query->whereBetween('price', [360, 450]);
                    break;
                case '450+':
                    $query->where('price', '>=', 450);
                    break;
            }
        }

        // Apply sorting
        $query->orderBy($this->sort_by, $this->sort_direction);

        $products = $query->paginate(12);

        // Get category counts for sidebar
        $categories = [
            'BIJILI CRACKERS' => Stock::active()->where('category', 'BIJILI CRACKERS')->where('quantity', '>', 0)->count(),
            'BOMBS' => Stock::active()->where('category', 'BOMBS')->where('quantity', '>', 0)->count(),
            'CHIT PUT' => Stock::active()->where('category', 'CHIT PUT')->where('quantity', '>', 0)->count(),
            'GIFT BOX' => Stock::active()->where('category', 'GIFT BOX')->where('quantity', '>', 0)->count(),
            'ROCKETS' => Stock::active()->where('category', 'ROCKETS')->where('quantity', '>', 0)->count(),
            'SINGLE FLASH' => Stock::active()->where('category', 'SINGLE FLASH')->where('quantity', '>', 0)->count(),
            'SPARKLERS' => Stock::active()->where('category', 'SPARKLERS')->where('quantity', '>', 0)->count(),
            'TWINKLING STAR' => Stock::active()->where('category', 'TWINKLING STAR')->where('quantity', '>', 0)->count(),
        ];

        return view('livewire.pages.shop-page', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
} 