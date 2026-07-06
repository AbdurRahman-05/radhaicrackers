<?php

namespace App\Http\Livewire\Admin;

use App\Models\Stock;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddStock extends Component
{
    use WithFileUploads;

    // Add component name for Livewire discovery
    protected static $componentName = 'admin.add-stock';

    public $item_name = '';
    public $description = '';
    public $quantity = 0;
    public $price = 0;
    public $original_price = 0;
    public $discount_percentage = 0;
    public $category = '';
    public $is_active = true;
    public $image;
    public $showSuccess = false;

    protected $rules = [
        'item_name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'original_price' => 'nullable|numeric|min:0',
        'discount_percentage' => 'nullable|integer|min:0|max:100',
        'category' => 'required|string|max:255',
        'is_active' => 'boolean',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
    ];

    protected $messages = [
        'item_name.required' => 'Product name is required.',
        'quantity.required' => 'Quantity is required.',
        'quantity.min' => 'Quantity must be at least 0.',
        'price.required' => 'Price is required.',
        'price.min' => 'Price must be at least 0.',
        'category.required' => 'Category is required.',
        'image.image' => 'The file must be an image.',
        'image.max' => 'Image size must be less than 2MB.',
        'image.mimes' => 'Image must be in JPEG, PNG, JPG, GIF, or WebP format.'
    ];

    public function getCategoriesProperty()
    {
        return [
            'BIJILI CRACKERS' => '⚡ Bijili Crackers',
            'BOMBS' => '💣 Bombs',
            'CHIT PUT' => '🎆 Chit Put',
            'GIFT BOX' => '🎁 Gift Box',
            'ROCKETS' => '🚀 Rockets',
            'SINGLE FLASH' => '⚡ Single Flash',
            'SPARKLERS' => '✨ Sparklers',
            'TWINKLING STAR' => '⭐ Twinkling Star'
        ];
    }

    public function calculateDiscount()
    {
        if ($this->original_price > 0 && $this->price > 0) {
            $this->discount_percentage = round((($this->original_price - $this->price) / $this->original_price) * 100);
        }
    }

    public function calculatePrice()
    {
        if ($this->original_price > 0 && $this->discount_percentage > 0) {
            $this->price = round($this->original_price * (1 - ($this->discount_percentage / 100)), 2);
        }
    }

    public function addStock()
    {
        $this->validate();

        try {
            $imagePath = null;
            if ($this->image) {
                $imagePath = $this->image->store('stocks', 'public');
            }

            Stock::create([
                'item_name' => $this->item_name,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'price' => $this->price,
                'original_price' => $this->original_price > 0 ? $this->original_price : null,
                'discount_percentage' => $this->discount_percentage > 0 ? $this->discount_percentage : null,
                'category' => $this->category,
                'is_active' => $this->is_active,
                'image' => $imagePath,
                'last_released_at' => now(),
                'next_release_at' => now()->addMinutes(10)
            ]);

            $this->showSuccess = true;
            $this->resetForm();
            
            session()->flash('success', 'Stock added successfully!');
            
            // Redirect to stocks list after 2 seconds
            $this->dispatch('stock-added');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add stock: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'item_name', 'description', 'quantity', 'price', 
            'original_price', 'discount_percentage', 'category', 
            'is_active', 'image'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.add-stock', [
            'categories' => $this->categories
        ])->layout('layouts.admin');
    }
} 