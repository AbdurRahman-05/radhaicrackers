<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class HomepageCategory extends Model
{
    protected $table = 'homepage_categories';

    protected $fillable = [
        'name',
        'search_term',
        'image',
        'product_count',
        'auto_count',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'product_count' => 'integer',
        'auto_count' => 'boolean',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Self-healing table creation for Hostinger shared hosting.
     */
    public static function createTableIfNotExists()
    {
        try {
            if (!Schema::hasTable('homepage_categories')) {
                Schema::create('homepage_categories', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('search_term')->nullable();
                    $table->string('image')->nullable();
                    $table->integer('product_count')->default(10);
                    $table->boolean('auto_count')->default(false);
                    $table->integer('sort_order')->default(0);
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();
                });

                // Seed default 2026 categories
                self::seedDefaults();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Could not auto-create homepage_categories table: ' . $e->getMessage());
        }
    }

    /**
     * Seed 7 standard 2026 categories
     */
    public static function seedDefaults()
    {
        $defaults = [
            [
                'name' => 'SINGLE FLASH',
                'search_term' => 'SINGLE FLASH',
                'image' => 'images/radhe_crackers_images_2026/single flash.png',
                'product_count' => 30,
                'auto_count' => false,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'BIJILI CRACKERS',
                'search_term' => 'BIJILI CRACKERS',
                'image' => 'images/radhe_crackers_images_2026/bijili crackers.png',
                'product_count' => 7,
                'auto_count' => false,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'BOMBS',
                'search_term' => 'BOMB',
                'image' => 'images/radhe_crackers_images_2026/bombs.png',
                'product_count' => 13,
                'auto_count' => false,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'ROCKETS',
                'search_term' => 'ROCKET',
                'image' => 'images/radhe_crackers_images_2026/rockets.png',
                'product_count' => 8,
                'auto_count' => false,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'SPARKLERS',
                'search_term' => 'SPARKLERS',
                'image' => 'images/radhe_crackers_images_2026/sparkles.png',
                'product_count' => 55,
                'auto_count' => false,
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'CHIT PUT',
                'search_term' => 'CHIT PUT',
                'image' => 'images/radhe_crackers_images_2026/chit put.png',
                'product_count' => 6,
                'auto_count' => false,
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'TWINKLING STAR',
                'search_term' => 'TWINKLING STAR',
                'image' => 'images/radhe_crackers_images_2026/twinkling star.png',
                'product_count' => 5,
                'auto_count' => false,
                'sort_order' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($defaults as $data) {
            self::updateOrCreate(['name' => $data['name']], $data);
        }
    }

    /**
     * Retrieve active categories with safe fallback to 2026 defaults
     */
    public static function getActiveCategories()
    {
        try {
            self::createTableIfNotExists();
            $categories = self::where('is_active', true)->orderBy('sort_order', 'asc')->get();
            if ($categories->isNotEmpty()) {
                return $categories;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('HomepageCategory::getActiveCategories fallback: ' . $e->getMessage());
        }

        // Resilient fallback objects (100% current year 2026 images)
        return collect([
            (object)[
                'id' => 1,
                'name' => 'SINGLE FLASH',
                'search_term' => 'SINGLE FLASH',
                'image' => 'images/radhe_crackers_images_2026/single flash.png',
                'image_url' => asset('images/radhe_crackers_images_2026/single flash.png'),
                'display_count' => 30,
                'product_count' => 30,
                'auto_count' => false,
                'sort_order' => 1,
                'is_active' => true,
            ],
            (object)[
                'id' => 2,
                'name' => 'BIJILI CRACKERS',
                'search_term' => 'BIJILI CRACKERS',
                'image' => 'images/radhe_crackers_images_2026/bijili crackers.png',
                'image_url' => asset('images/radhe_crackers_images_2026/bijili crackers.png'),
                'display_count' => 7,
                'product_count' => 7,
                'auto_count' => false,
                'sort_order' => 2,
                'is_active' => true,
            ],
            (object)[
                'id' => 3,
                'name' => 'BOMBS',
                'search_term' => 'BOMB',
                'image' => 'images/radhe_crackers_images_2026/bombs.png',
                'image_url' => asset('images/radhe_crackers_images_2026/bombs.png'),
                'display_count' => 13,
                'product_count' => 13,
                'auto_count' => false,
                'sort_order' => 3,
                'is_active' => true,
            ],
            (object)[
                'id' => 4,
                'name' => 'ROCKETS',
                'search_term' => 'ROCKET',
                'image' => 'images/radhe_crackers_images_2026/rockets.png',
                'image_url' => asset('images/radhe_crackers_images_2026/rockets.png'),
                'display_count' => 8,
                'product_count' => 8,
                'auto_count' => false,
                'sort_order' => 4,
                'is_active' => true,
            ],
            (object)[
                'id' => 5,
                'name' => 'SPARKLERS',
                'search_term' => 'SPARKLERS',
                'image' => 'images/radhe_crackers_images_2026/sparkles.png',
                'image_url' => asset('images/radhe_crackers_images_2026/sparkles.png'),
                'display_count' => 55,
                'product_count' => 55,
                'auto_count' => false,
                'sort_order' => 5,
                'is_active' => true,
            ],
            (object)[
                'id' => 6,
                'name' => 'CHIT PUT',
                'search_term' => 'CHIT PUT',
                'image' => 'images/radhe_crackers_images_2026/chit put.png',
                'image_url' => asset('images/radhe_crackers_images_2026/chit put.png'),
                'display_count' => 6,
                'product_count' => 6,
                'auto_count' => false,
                'sort_order' => 6,
                'is_active' => true,
            ],
            (object)[
                'id' => 7,
                'name' => 'TWINKLING STAR',
                'search_term' => 'TWINKLING STAR',
                'image' => 'images/radhe_crackers_images_2026/twinkling star.png',
                'image_url' => asset('images/radhe_crackers_images_2026/twinkling star.png'),
                'display_count' => 5,
                'product_count' => 5,
                'auto_count' => false,
                'sort_order' => 7,
                'is_active' => true,
            ],
        ]);
    }

    /**
     * Get image URL accessor
     */
    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return asset('images/firework-default.png');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (str_starts_with($this->image, 'images/') || str_starts_with($this->image, 'assets/')) {
            return asset($this->image);
        }

        if (str_starts_with($this->image, 'storage/')) {
            return asset($this->image);
        }

        return asset('storage/' . $this->image);
    }

    /**
     * Get display product count (either auto-calculated from active products or custom)
     */
    public function getDisplayCountAttribute()
    {
        if ($this->auto_count && !empty($this->search_term)) {
            try {
                $term = $this->search_term;
                $count = Stock::where('is_active', 1)
                    ->where(function ($q) use ($term) {
                        $q->where('category', 'LIKE', "%{$term}%")
                          ->orWhere('item_name', 'LIKE', "%{$term}%");
                    })
                    ->count();

                if ($count > 0) {
                    return $count;
                }
            } catch (\Exception $e) {
                // Ignore and fall back
            }
        }

        return $this->product_count ?: 0;
    }

    /**
     * List all available 2026 current year image choices for the admin picker
     */
    public static function getAvailable2026Images()
    {
        $images = [
            [
                'title' => 'Single Flash (2026)',
                'path' => 'images/radhe_crackers_images_2026/single flash.png',
                'url' => asset('images/radhe_crackers_images_2026/single flash.png'),
                'badge' => '2026 High-Res',
            ],
            [
                'title' => 'Bijili Crackers (2026)',
                'path' => 'images/radhe_crackers_images_2026/bijili crackers.png',
                'url' => asset('images/radhe_crackers_images_2026/bijili crackers.png'),
                'badge' => '2026 High-Res',
            ],
            [
                'title' => 'Bombs (2026)',
                'path' => 'images/radhe_crackers_images_2026/bombs.png',
                'url' => asset('images/radhe_crackers_images_2026/bombs.png'),
                'badge' => '2026 High-Res',
            ],
            [
                'title' => 'Rockets (2026)',
                'path' => 'images/radhe_crackers_images_2026/rockets.png',
                'url' => asset('images/radhe_crackers_images_2026/rockets.png'),
                'badge' => '2026 High-Res',
            ],
            [
                'title' => 'Sparklers (2026)',
                'path' => 'images/radhe_crackers_images_2026/sparkles.png',
                'url' => asset('images/radhe_crackers_images_2026/sparkles.png'),
                'badge' => '2026 High-Res',
            ],
            [
                'title' => 'Chit Put (2026)',
                'path' => 'images/radhe_crackers_images_2026/chit put.png',
                'url' => asset('images/radhe_crackers_images_2026/chit put.png'),
                'badge' => '2026 High-Res',
            ],
            [
                'title' => 'Twinkling Star (2026)',
                'path' => 'images/radhe_crackers_images_2026/twinkling star.png',
                'url' => asset('images/radhe_crackers_images_2026/twinkling star.png'),
                'badge' => '2026 High-Res',
            ],
            [
                'title' => 'Kids Special (2026)',
                'path' => 'images/radhe_crackers_images_2026/kids special.png',
                'url' => asset('images/radhe_crackers_images_2026/kids special.png'),
                'badge' => '2026 High-Res',
            ],
        ];

        // Also append any active current year stock images
        try {
            $activeStocks = Stock::where('is_active', 1)
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->get(['id', 'item_name', 'image']);

            foreach ($activeStocks as $stk) {
                $images[] = [
                    'title' => $stk->item_name . ' (Active Stock)',
                    'path' => $stk->image,
                    'url' => $stk->image_url,
                    'badge' => 'Active Product',
                ];
            }
        } catch (\Exception $e) {
            // Ignore
        }

        return $images;
    }
}
