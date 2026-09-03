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
        
        // Auto-fix and sequence order_within_category for any categories having 0, null, or unsequenced items
        foreach ($categories as $category) {
            $categoryStocks = Stock::where('is_active', true)
                ->where('category', $category->name)
                ->orderByRaw('CASE WHEN order_within_category IS NULL OR order_within_category <= 0 THEN 999999 ELSE order_within_category END ASC')
                ->orderBy('id', 'asc')
                ->get();

            $needsFix = false;
            $expected = 1;
            foreach ($categoryStocks as $s) {
                if ((int)$s->order_within_category !== $expected) {
                    $needsFix = true;
                    break;
                }
                $expected++;
            }

            if ($needsFix && $categoryStocks->isNotEmpty()) {
                $idx = 1;
                foreach ($categoryStocks as $s) {
                    Stock::where('id', $s->id)->update(['order_within_category' => $idx]);
                    $idx++;
                }
            }
        }

        // Get all active stocks ordered by order_within_category
        $stocks = Stock::where('is_active', true)
            ->orderByRaw('CASE WHEN order_within_category IS NULL OR order_within_category <= 0 THEN 999999 ELSE order_within_category END ASC')
            ->orderBy('id', 'asc')
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
                $stockArray = $stock->toArray();
                $currentCount = count($orderedGroups[$stock->category]['items']) + 1;
                $stockArray['order_within_category'] = max(1, (int)($stockArray['order_within_category'] ?? $currentCount));
                $orderedGroups[$stock->category]['items'][] = $stockArray;
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
        $this->editingOrder = max(1, (int)$currentOrder);
    }

    public function saveOrder()
    {
        if (!$this->editingItem || !is_numeric($this->editingOrder)) {
            return;
        }

        $stock = Stock::find($this->editingItem);
        if ($stock) {
            $newOrder = max(1, (int)$this->editingOrder);
            $categoryName = $stock->category;

            $otherStocks = Stock::where('is_active', true)
                ->where('category', $categoryName)
                ->where('id', '!=', $stock->id)
                ->orderByRaw('CASE WHEN order_within_category IS NULL OR order_within_category <= 0 THEN 999999 ELSE order_within_category END ASC')
                ->orderBy('id', 'asc')
                ->get();

            $orderedList = [];
            $targetPos = min(count($otherStocks) + 1, $newOrder);
            $currentPos = 1;
            $inserted = false;

            foreach ($otherStocks as $other) {
                if ($currentPos === $targetPos && !$inserted) {
                    $orderedList[] = $stock->id;
                    $inserted = true;
                }
                $orderedList[] = $other->id;
                $currentPos++;
            }
            if (!$inserted) {
                $orderedList[] = $stock->id;
            }

            foreach ($orderedList as $index => $id) {
                Stock::where('id', $id)->update(['order_within_category' => $index + 1]);
            }
        }

        $this->editingItem = null;
        $this->editingOrder = null;
        $this->loadStocks();
    }

    public function cancelEdit()
    {
        $this->editingItem = null;
        $this->editingOrder = null;
    }

    public function updateOrder($categoryName, $orderedIds)
    {
        if (!is_array($orderedIds)) {
            return;
        }

        $index = 1;
        foreach ($orderedIds as $item) {
            $id = is_array($item) ? ($item['value'] ?? $item['id'] ?? null) : $item;
            if ($id) {
                Stock::where('id', $id)->update(['order_within_category' => $index]);
                $index++;
            }
        }
        
        $this->loadStocks();
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
