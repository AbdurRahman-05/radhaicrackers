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
        $categories = Category::orderBy('name')->paginate(10);
        $parentCategories = Category::where('is_active', true)->orderBy('name')->get();

        return view('livewire.admin.categories', [
            'categories' => $categories,
            'parentCategories' => $parentCategories,
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
            if ($this->editing && $this->selectedCategoryId) {
                // Update existing category
                $category = Category::findOrFail($this->selectedCategoryId);
                $category->update([
                    'name' => $this->name,
                    'slug' => $this->slug,
                    'description' => $this->description,
                    'parent_id' => $this->parent_id ?: null,
                    'is_active' => $this->is_active,
                    'sort_order' => $this->sort_order,
                    'icon' => $this->icon,
                    'color' => $this->color,
                ]);

                session()->flash('success', 'Category updated successfully!');
            } else {
                // Create new category
                Category::create([
                    'name' => $this->name,
                    'slug' => $this->slug,
                    'description' => $this->description,
                    'parent_id' => $this->parent_id ?: null,
                    'is_active' => $this->is_active,
                    'sort_order' => $this->sort_order,
                    'icon' => $this->icon,
                    'color' => $this->color,
                ]);

                session()->flash('success', 'Category created successfully!');
            }

            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while saving the category: ' . $e->getMessage());
        }
    }

    public function delete($categoryId)
    {
        try {
            $category = Category::findOrFail($categoryId);
            $this->categoryToDelete = $category;
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
            
            // Check if category has children
            if ($category->hasChildren()) {
                session()->flash('error', 'Cannot delete category with subcategories. Please delete subcategories first.');
                return;
            }

            // Check if category has stocks
            if ($category->stocks()->exists()) {
                session()->flash('error', 'Cannot delete category that has products. Please reassign or delete products first.');
                return;
            }

            $categoryName = $category->name;
            $category->delete();
            session()->flash('success', "Category '{$categoryName}' deleted successfully!");
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
