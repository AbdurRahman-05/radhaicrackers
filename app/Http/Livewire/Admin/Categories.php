<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Categories extends Component
{
    use WithPagination;

    // Component properties
    public $showModal = false;
    public $showDeleteModal = false;
    public $editing = false;
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedCategoryId = null;
    public $categoryToDelete = null;

    public $selected_year = '';
    public $available_years = [];

    public function mount()
    {
        $this->selected_year = (string)date('Y');
        $orderYears = \App\Models\Order::selectRaw('YEAR(created_at) as year')
            ->distinct()->orderBy('year', 'desc')->pluck('year')->toArray();
        $stockYears = \App\Models\Stock::selectRaw('YEAR(created_at) as year')
            ->distinct()->orderBy('year', 'desc')->pluck('year')->toArray();

        $years = array_values(array_unique(array_merge($orderYears, $stockYears, [(int)date('Y'), 2025])));
        rsort($years);
        $this->available_years = $years;
    }

    // Form properties
    public $name = '';
    public $slug = '';
    public $description = '';
    public $parent_id = null;
    public $is_active = true;
    public $sort_order = 0;
    public $icon = '';
    public $color = '';

    // Validation rules
    protected function rules()
    {
        $categoryId = $this->selectedCategoryId;
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($categoryId)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($categoryId)],
            'description' => 'nullable|string|max:1000',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
        ];
    }

    // Validation messages
    protected $messages = [
        'name.required' => 'Category name is required.',
        'name.unique' => 'This category name already exists.',
        'slug.required' => 'Slug is required.',
        'slug.unique' => 'This slug already exists.',
        'parent_id.exists' => 'Selected parent category does not exist.',
    ];

    // Real-time validation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // Auto-generate slug from name
        if ($propertyName === 'name' && empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }
    }


   public function render()
    {
        $query = Category::query();
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('slug', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }
        $categories = $query->orderBy($this->sortField, $this->sortDirection)
            ->orderBy('sort_order')
            ->paginate(10);

        // Compute active stock counts per category filtered by selected year
        $selectedYear = $this->selected_year;
        foreach ($categories as $category) {
            $category->stocks_count = \App\Models\Stock::query()
                ->where('is_active', true)
                ->where(function($q) use ($category) {
                    $q->where('category', $category->name)
                      ->orWhere('category_id', $category->id)
                      ->orWhere('category', (string)$category->id);
                })
                ->when($selectedYear !== '' && $selectedYear !== null && $selectedYear !== 'all', function($q) use ($selectedYear) {
                    $q->whereYear('created_at', $selectedYear);
                })
                ->count();
        }

        $parentCategories = Category::where('is_active', true)->orderBy('name')->get();

        return view('livewire.admin.categories', [
            'categories' => $categories,
            'parentCategories' => $parentCategories,
            'available_years' => $this->available_years,
        ])->layout('layouts.admin');
    }


    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editing = false;
    }

    public function edit($categoryId)
    {
        try {
            $category = Category::findOrFail($categoryId);
            $this->selectedCategoryId = $category->id;
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->description = $category->description ?? '';
            $this->parent_id = $category->parent_id;
            $this->is_active = $category->is_active;
            $this->sort_order = $category->sort_order ?? 0;
            $this->icon = $category->icon ?? '';
            $this->color = $category->color ?? '';
            
            $this->showModal = true;
            $this->editing = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Category not found or could not be loaded.');
        }
    }

    public function save()
    {
        $this->validate();

        try {
            $newSortOrder = max(1, (int)$this->sort_order);
            $maxOrder = Category::max('sort_order');
            if ($newSortOrder > $maxOrder + ($this->editing ? 0 : 1)) {
                $newSortOrder = $maxOrder + 1;
            }

            if ($this->editing && $this->selectedCategoryId) {
                $category = Category::findOrFail($this->selectedCategoryId);
                if ($category->sort_order != $newSortOrder) {
                    if ($category->sort_order < $newSortOrder) {
                        // Move down: decrement others between old and new
                        Category::where('sort_order', '>', $category->sort_order)
                            ->where('sort_order', '<=', $newSortOrder)
                            ->decrement('sort_order');
                    } else {
                        // Move up: increment others between new and old
                        Category::where('sort_order', '>=', $newSortOrder)
                            ->where('sort_order', '<', $category->sort_order)
                            ->increment('sort_order');
                    }
                }
                $category->update([
                    'name' => $this->name,
                    'slug' => $this->slug,
                    'description' => $this->description,
                    'parent_id' => $this->parent_id ?: null,
                    'is_active' => $this->is_active,
                    'sort_order' => $newSortOrder,
                    'icon' => $this->icon,
                    'color' => $this->color,
                ]);
                session()->flash('success', 'Category updated successfully!');
            } else {
                // Insert new: increment all at or above newSortOrder
                Category::where('sort_order', '>=', $newSortOrder)
                    ->increment('sort_order');
                Category::create([
                    'name' => $this->name,
                    'slug' => $this->slug,
                    'description' => $this->description,
                    'parent_id' => $this->parent_id ?: null,
                    'is_active' => $this->is_active,
                    'sort_order' => $newSortOrder,
                    'icon' => $this->icon,
                    'color' => $this->color,
                ]);
                session()->flash('success', 'Category created successfully!');
            }
            // Resequence all sort_order values to be continuous starting from 1
            $all = Category::orderBy('sort_order')->get();
            $i = 1;
            foreach ($all as $cat) {
                if ($cat->sort_order != $i) {
                    $cat->sort_order = $i;
                    $cat->save();
                }
                $i++;
            }
            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while saving the category: ' . $e->getMessage());
        }
    }

    public function updatedSelectedYear()
    {
        $this->resetPage();
    }

    public function delete($categoryId)
    {
        try {
            $category = Category::findOrFail($categoryId);
            $this->categoryToDelete = $category;
            $selectedYear = $this->selected_year;
            $this->productCount = \App\Models\Stock::where(function($q) use ($category) {
                    $q->where('category', $category->name)
                      ->orWhere('category_id', $category->id)
                      ->orWhere('category', (string)$category->id);
                })
                ->when($selectedYear !== '' && $selectedYear !== null && $selectedYear !== 'all', function($q) use ($selectedYear) {
                    $q->whereYear('created_at', $selectedYear);
                })
                ->count();
            $this->deleteAction = 'uncategorize';
            $this->reassignCategoryId = '';
            $this->showDeleteModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while loading the category: ' . $e->getMessage());
        }
    }

    public function confirmDelete()
    {
        try {
            if (!$this->categoryToDelete) {
                session()->flash('error', 'Category not found.');
                return;
            }

            $category = $this->categoryToDelete;
            $selectedYear = $this->selected_year;
            
            // Reassign any subcategories to parent
            if ($category->hasChildren()) {
                Category::where('parent_id', $category->id)->update(['parent_id' => $category->parent_id]);
            }

            // Handle associated products isolated by selected year (if year selected) or all
            $stocksQuery = \App\Models\Stock::where(function($q) use ($category) {
                    $q->where('category', $category->name)
                      ->orWhere('category_id', $category->id)
                      ->orWhere('category', (string)$category->id);
                })
                ->when($selectedYear !== '' && $selectedYear !== null && $selectedYear !== 'all', function($q) use ($selectedYear) {
                    $q->whereYear('created_at', $selectedYear);
                });
            
            $stockCount = $stocksQuery->count();

            if ($stockCount > 0) {
                if ($this->deleteAction === 'delete_products') {
                    // Delete only the products in the selected scope
                    $stocksQuery->delete();
                } elseif ($this->deleteAction === 'reassign' && !empty($this->reassignCategoryId)) {
                    $targetCategory = Category::find($this->reassignCategoryId);
                    if ($targetCategory) {
                        $stocksQuery->update([
                            'category' => $targetCategory->name,
                            'category_id' => $targetCategory->id,
                        ]);
                    }
                } else {
                    // Uncategorize products
                    $stocksQuery->update([
                        'category' => 'Uncategorized',
                        'category_id' => null,
                    ]);
                }
            }

            // If deleting for all years or category has no products in other years, delete category
            $otherYearsCount = \App\Models\Stock::where(function($q) use ($category) {
                    $q->where('category', $category->name)
                      ->orWhere('category_id', $category->id)
                      ->orWhere('category', (string)$category->id);
                })
                ->count();

            $categoryName = $category->name;
            $deletedSortOrder = $category->sort_order;

            if ($otherYearsCount === 0 || empty($selectedYear)) {
                $category->delete();
                Category::where('sort_order', '>', $deletedSortOrder)->decrement('sort_order');
                session()->flash('success', "Category '{$categoryName}' deleted successfully!");
            } else {
                session()->flash('success', "Category '{$categoryName}' cleared for {$selectedYear} products!");
            }

            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the category: ' . $e->getMessage());
        } finally {
            $this->closeDeleteModal();
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->categoryToDelete = null;
        $this->productCount = 0;
        $this->deleteAction = 'delete_products';
        $this->reassignCategoryId = '';
    }

    public function toggleStatus($categoryId)
    {
        try {
            $category = Category::findOrFail($categoryId);
            $newStatus = !$category->is_active;
            $category->update(['is_active' => $newStatus]);
            
            $status = $newStatus ? 'activated' : 'deactivated';
            session()->flash('success', "Category '{$category->name}' {$status} successfully!");
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating the category status: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->selectedCategoryId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->parent_id = null;
        $this->is_active = true;
        $this->sort_order = 0;
        $this->icon = '';
        $this->color = '';
        $this->resetValidation();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
