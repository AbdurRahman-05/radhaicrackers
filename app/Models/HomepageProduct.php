<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageProduct extends Model
{
    protected $table = 'homepage_products';

    protected $fillable = [
        'item_name',
        'category',
        'description',
        'original_price',
        'discount_percentage',
        'special_discount_percentage',
        'price',
        'quantity',
        'is_active',
        'image',
        'youtube_url',
        'is_popular',
        'is_latest',
    ];

    public $timestamps = true;

    protected $appends = ['image_url'];

    public function categoryRelation()
    {
        return $this->belongsTo(Category::class, 'category', 'id');
    }

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return asset('images/firework-default.png');
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        $cleanPath = ltrim($this->image, '/');

        if (str_starts_with($cleanPath, 'public/storage/')) {
            $cleanPath = substr($cleanPath, 15);
        } elseif (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        } elseif (str_starts_with($cleanPath, 'public/')) {
            $cleanPath = substr($cleanPath, 7);
        }

        return asset('storage/' . $cleanPath);
    }
} 