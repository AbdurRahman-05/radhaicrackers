<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
