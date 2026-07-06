<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Stock;
use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StockOrdering extends Component
{
    public $categoryGroups;
    public $editingItem = null;
    public $editingOrder;
    public $categories;

    public function mount()
    {
        $this->loadCategories();
        $this->loadStocks();
    }

    public function loadCategories()
    {
        // Get categories with their order from the database
        $categories = \App\Models\Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'sort_order']);

        $this->categories = $categories->toArray();
    }

    public function loadStocks()
    {
        // Get categories first to maintain order
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        // Get all active stocks with selected fields
        $stocks = Stock::where('is_active', true)
            ->orderBy('order_within_category')
            ->orderBy('item_name')
            ->get(['id', 'item_name', 'category', 'order_within_category', 'image']);

        // Initialize ordered groups with all categories
        $orderedGroups = [];
        foreach ($categories as $category) {
            $orderedGroups[$category->name] = [
                'order' => $category->sort_order,
                'items' => []
            ];
        }

        // Group stocks by category
        foreach ($stocks as $stock) {
            if (isset($orderedGroups[$stock->category])) {
                $orderedGroups[$stock->category]['items'][] = $stock->toArray();
            }
        }

        // Remove empty categories
        $orderedGroups = array_filter($orderedGroups, function($group) {
            return !empty($group['items']);
        });

        $this->categoryGroups = $orderedGroups;
    }

    public function startEditing($itemId, $currentOrder)
    {
        $this->editingItem = $itemId;
        $this->editingOrder = $currentOrder;
    }

    public function saveOrder()
    {
        if (!$this->editingItem || !is_numeric($this->editingOrder)) {
            return;
        }

        Stock::where('id', $this->editingItem)
            ->update(['order_within_category' => max(1, (int)$this->editingOrder)]);

        $this->editingItem = null;
        $this->editingOrder = null;
        $this->loadStocks();
    }

    public function cancelEdit()
    {
        $this->editingItem = null;
        $this->editingOrder = null;
    }

    public function updateOrder($categoryId, $items)
    {
        if (!is_array($items)) {
            return;
        }
        
        // Extract the IDs from items array
        $orderedIds = array_map(function($item) {
            return $item['value'] ?? null;
        }, $items);
        
        // Filter out any null values
        $orderedIds = array_filter($orderedIds);
        
        // Update order for each item in the category
        foreach ($orderedIds as $index => $id) {
            Stock::where('id', $id)
                ->update(['order_within_category' => $index + 1]); // Start from 1
        }
        
        // Refresh the data
        $this->loadStocks();
        
        $this->dispatch('orderUpdated');
    }

    protected function getCategoryOrder($categoryName)
    {
        $category = Category::where('name', $categoryName)->first();
        return $category ? $category->sort_order : PHP_INT_MAX;
    }

    public function render()
    {
        return view('livewire.admin.stock-ordering');
    }
}
