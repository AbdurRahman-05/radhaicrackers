<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HeroSlide extends Model
{
    protected $table = 'hero_slides';

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'link_url',
        'button_text',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = ['image_url'];

    /**
     * Auto-create table on live hosting if migrations haven't run
     */
    public static function createTableIfNotExists()
    {
        try {
            if (!Schema::hasTable('hero_slides')) {
                Schema::create('hero_slides', function (Blueprint $table) {
                    $table->id();
                    $table->string('title')->nullable();
                    $table->string('subtitle')->nullable();
                    $table->string('image');
                    $table->string('link_url')->nullable()->default('/quotation');
                    $table->string('button_text')->nullable()->default('Order Now');
                    $table->integer('sort_order')->default(0);
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();
                });

                // Seed initial default slides using high quality 2026 banner images (Home carousel only)
                DB::table('hero_slides')->insert([
                    [
                        'title' => 'Festival of Lights Celebration',
                        'subtitle' => 'Premium Sivakasi Crackers Direct to Your Doorstep',
                        'image' => 'images/radhe_crackers_images_2026/home carosel 1.png',
                        'link_url' => '/quotation',
                        'button_text' => 'Shop Now',
                        'sort_order' => 1,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Exclusive Festive Mega Offers',
                        'subtitle' => 'Enjoy Up to 70% + 15% Special Discount This Season',
                        'image' => 'images/radhe_crackers_images_2026/home carosel 2.png',
                        'link_url' => '/quotation',
                        'button_text' => 'Explore Offers',
                        'sort_order' => 2,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            } else {
                // Ensure any previously seeded combo banner is removed from hero slider table
                try {
                    DB::table('hero_slides')
                        ->where('image', 'like', '%combo packs banner%')
                        ->orWhere('link_url', 'like', '%/combos%')
                        ->delete();
                } catch (\Throwable $ex) {
                    // Ignore if DB issue
                }
            }
        } catch (\Throwable $e) {
            // Silently ignore if table already created concurrently
        }
    }

    /**
     * Safely get active slides without crashing even if table doesn't exist yet
     */
    public static function getActiveSlides()
    {
        try {
            self::createTableIfNotExists();
            return static::where('is_active', true)
                ->where('image', 'not like', '%combo%')
                ->orderBy('sort_order', 'asc')
                ->get();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return asset('images/radhe_crackers_images_2026/home carosel 1.png');
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        $cleanPath = ltrim($this->image, '/');

        // Check if direct asset path like images/... or hero/...
        if (str_starts_with($cleanPath, 'images/') || str_starts_with($cleanPath, 'hero/') || str_starts_with($cleanPath, 'front/')) {
            return asset($cleanPath);
        }

        // Clean storage prefixes
        if (str_starts_with($cleanPath, 'public/storage/')) {
            $cleanPath = substr($cleanPath, 15);
        } elseif (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        } elseif (str_starts_with($cleanPath, 'public/')) {
            $cleanPath = substr($cleanPath, 7);
        }

        \App\Models\Stock::syncUploadedFile($cleanPath);

        return asset('storage/' . $cleanPath);
    }
}
