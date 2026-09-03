<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'is_active',
        'sort_order',
        'icon',
        'color',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot method to automatically generate unique slug from name
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $baseSlug = !empty($category->slug) ? Str::slug($category->slug) : Str::slug($category->name);
            if (empty($baseSlug)) {
                $baseSlug = 'category';
            }
            $slug = $baseSlug;
            $counter = 1;
            while (static::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $category->slug = $slug;
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $baseSlug = Str::slug($category->name) ?: 'category';
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                $category->slug = $slug;
            }
        });
    }

    /**
     * Find existing category by name, slug, ID or normalized string, or create safely
     */
    public static function findOrCreateByName($rawName): ?self
    {
        $cleanName = trim((string)$rawName);
        if ($cleanName === '') {
            return null;
        }

        // 1. Direct ID match
        if (is_numeric($cleanName)) {
            $cat = static::find($cleanName);
            if ($cat) return $cat;
        }

        // 2. Direct case-insensitive match on name
        $cat = static::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($cleanName)])->first();
        if ($cat) return $cat;

        // 3. Match by slug
        $slug = Str::slug($cleanName);
        if (!empty($slug)) {
            $cat = static::where('slug', $slug)->first();
            if ($cat) return $cat;
        }

        // 4. Match without punctuation / quotes (e.g. 2" SINGLE COLOUR SHOT vs 2 SINGLE COLOUR SHOT)
        $sanitized = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $cleanName));
        if ($sanitized !== '') {
            $allCategories = static::all();
            foreach ($allCategories as $c) {
                $existingSanitized = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $c->name));
                if ($existingSanitized === $sanitized) {
                    return $c;
                }
            }
        }

        // 5. Create new category with auto-handled unique slug
        $maxSort = static::max('sort_order') ?? 0;
        try {
            return static::create([
                'name' => $cleanName,
                'slug' => $slug ?: 'category',
                'is_active' => true,
                'sort_order' => $maxSort + 1,
            ]);
        } catch (\Exception $e) {
            return static::where('slug', $slug)
                ->orWhereRaw('LOWER(TRIM(name)) = ?', [strtolower($cleanName)])
                ->first();
        }
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for root categories (no parent)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope for ordering by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get parent category
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get child categories
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->ordered();
    }

    /**
     * Get all descendants (children, grandchildren, etc.)
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get all ancestors (parent, grandparent, etc.)
     */
    public function ancestors()
    {
        return $this->parent()->with('ancestors');
    }

    /**
     * Get all stocks in this category
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'category', 'name');
    }

    /**
     * Get active stocks in this category
     */
    public function activeStocks()
    {
        return $this->stocks()->active();
    }

    /**
     * Check if category has children
     */
    public function hasChildren()
    {
        return $this->children()->exists();
    }

    /**
     * Check if category is a root category
     */
    public function isRoot()
    {
        return is_null($this->parent_id);
    }

    /**
     * Get the full path of the category (parent > child)
     */
    public function getFullPathAttribute()
    {
        $path = [$this->name];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }

        return implode(' > ', $path);
    }

    /**
     * Get category level (0 for root, 1 for first level, etc.)
     */
    public function getLevelAttribute()
    {
        $level = 0;
        $parent = $this->parent;

        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }

        return $level;
    }

    /**
     * Get all categories as a tree structure
     */
    public static function getTree()
    {
        return static::root()->with('children')->ordered()->get();
    }

    /**
     * Get categories for select dropdown
     */
    public static function getForSelect()
    {
        return static::active()->ordered()->pluck('name', 'id');
    }

    /**
     * Generate unique slug
     */
    public function generateUniqueSlug()
    {
        $baseSlug = Str::slug($this->name);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
