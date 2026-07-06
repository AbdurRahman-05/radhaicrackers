<?php

namespace App\Http\Livewire\Pages;

use Livewire\Component;
use App\Models\Stock;

class Search extends Component
{
    public $search = '';
    public $showResults = false;
    public $results = [];

    protected $queryString = ['search'];

    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $this->searchProducts();
            $this->showResults = true;
        } else {
            $this->results = [];
            $this->showResults = false;
        }
    }

    public function searchProducts()
    {
        $this->results = Stock::active()
            ->where(function ($query) {
                $query->where('item_name', 'like', '%' . $this->search . '%')
                      ->orWhere('category', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->take(8)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->item_name,
                    'category' => $product->category,
                    'price' => $product->price,
                    'original_price' => $product->original_price,
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

    public function selectProduct($productId)
    {
        $this->search = '';
        $this->showResults = false;
        $this->results = [];
        
        // Redirect to order form
        return redirect()->route('order.form');
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->showResults = false;
        $this->results = [];
    }

    public function render()
    {
        return view('livewire.pages.search');
    }
} 