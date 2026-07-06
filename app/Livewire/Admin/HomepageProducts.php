<?php

namespace App\Livewire\Admin;

use App\Models\HomepageProduct;
use Livewire\Component;
use Livewire\WithFileUploads;

class HomepageProducts extends Component
{
    use WithFileUploads;

    public $products;
    public $showModal = false;
    public $isEdit = false;
    public $productId;
    public $item_name, $category, $description, $original_price, $discount_percentage, $special_discount_percentage, $price, $quantity, $is_active, $image, $youtube_url, $is_popular, $is_latest, $image_path;

    protected $rules = [
        'item_name' => 'required|string|max:255',
        'category' => 'required|integer',
        'description' => 'nullable|string',
        'original_price' => 'nullable|numeric',
        'discount_percentage' => 'nullable|integer',
        'special_discount_percentage' => 'nullable|integer',
        'price' => 'required|numeric',
        'quantity' => 'required|integer',
        'is_active' => 'boolean',
        'image' => 'nullable|image|max:2048',
        'youtube_url' => 'nullable|string|max:255',
        'is_popular' => 'boolean',
        'is_latest' => 'boolean',
    ];

    public function mount() {
        $this->resetForm();
        $this->loadProducts();
    }

    public function loadProducts() {
        $this->products = HomepageProduct::orderBy('created_at', 'desc')->get();
    }

    public function showCreateModal() {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function showEditModal($id) {
        $product = HomepageProduct::findOrFail($id);
        $this->productId = $product->id;
        $this->item_name = $product->item_name;
        $this->category = $product->category;
        $this->description = $product->description;
        $this->original_price = $product->original_price;
        $this->discount_percentage = $product->discount_percentage;
        $this->special_discount_percentage = $product->special_discount_percentage;
        $this->price = $product->price;
        $this->quantity = $product->quantity;
        $this->is_active = $product->is_active;
        $this->youtube_url = $product->youtube_url;
        $this->is_popular = $product->is_popular;
        $this->is_latest = $product->is_latest;
        $this->image_path = $product->image;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function saveProduct() {
        $this->validate();
        $imagePath = $this->image ? $this->image->store('homepage_products', 'public') : null;
        HomepageProduct::create([
            'item_name' => $this->item_name,
            'category' => $this->category,
            'description' => $this->description,
            'original_price' => $this->original_price,
            'discount_percentage' => $this->discount_percentage,
            'special_discount_percentage' => $this->special_discount_percentage,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'is_active' => $this->is_active ?? false,
            'image' => $imagePath,
            'youtube_url' => $this->youtube_url,
            'is_popular' => $this->is_popular ?? false,
            'is_latest' => $this->is_latest ?? false,
        ]);
        $this->showModal = false;
        $this->resetForm();
        $this->loadProducts();
    }

    public function updateProduct() {
        $this->validate();
        $product = HomepageProduct::findOrFail($this->productId);
        $imagePath = $this->image ? $this->image->store('homepage_products', 'public') : $product->image;
        $product->update([
            'item_name' => $this->item_name,
            'category' => $this->category,
            'description' => $this->description,
            'original_price' => $this->original_price,
            'discount_percentage' => $this->discount_percentage,
            'special_discount_percentage' => $this->special_discount_percentage,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'is_active' => $this->is_active ?? false,
            'image' => $imagePath,
            'youtube_url' => $this->youtube_url,
            'is_popular' => $this->is_popular ?? false,
            'is_latest' => $this->is_latest ?? false,
        ]);
        $this->showModal = false;
        $this->resetForm();
        $this->loadProducts();
    }

    public function editProduct($id) {
        $this->showEditModal($id);
    }

    public function deleteProduct($id) {
        HomepageProduct::findOrFail($id)->delete();
        $this->loadProducts();
    }

    public function resetForm() {
        $this->productId = null;
        $this->item_name = '';
        $this->category = '';
        $this->description = '';
        $this->original_price = '';
        $this->discount_percentage = '';
        $this->special_discount_percentage = '';
        $this->price = '';
        $this->quantity = 0;
        $this->is_active = true;
        $this->image = null;
        $this->youtube_url = '';
        $this->is_popular = false;
        $this->is_latest = false;
        $this->image_path = null;
    }

    public function render()
    {
        return view('livewire.admin.homepage-products');
    }
}
