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
} 