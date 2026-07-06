<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stock;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $stocks = [
            // Popular Products
            [
                'item_name' => 'Hydro Bomb / ஹைட்ரோ பாம்',
                'quantity' => 100,
                'price' => 61.00,
                'original_price' => 240.00,
                'discount_percentage' => 75,
                'category' => 'BOMBS',
                'description' => '1 Box/10 Pcs',
                'is_active' => true,
            ],
            [
                'item_name' => 'Bullet Bomb / புல்லட் பாம்',
                'quantity' => 150,
                'price' => 31.00,
                'original_price' => 120.00,
                'discount_percentage' => 74,
                'category' => 'BOMBS',
                'description' => '1 Box/10 Pcs',
                'is_active' => true,
            ],
            [
                'item_name' => '4" Lakshmi / 4" லட்சுமி',
                'quantity' => 200,
                'price' => 17.00,
                'original_price' => 68.00,
                'discount_percentage' => 75,
                'category' => 'SINGLE FLASH',
                'description' => '1 Pkt/10 Pcs',
                'is_active' => true,
            ],
            [
                'item_name' => '4" Gold Lakshmi / 4" கோல்டு லட்சுமி',
                'quantity' => 120,
                'price' => 29.00,
                'original_price' => 114.00,
                'discount_percentage' => 75,
                'category' => 'SINGLE FLASH',
                'description' => '1 Pkt/10 Pcs',
                'is_active' => true,
            ],
            [
                'item_name' => 'Whistling Rocket / விசிலிங் ராக்கெட்',
                'quantity' => 80,
                'price' => 184.00,
                'original_price' => 720.00,
                'discount_percentage' => 74,
                'category' => 'ROCKETS',
                'description' => '1 Box/5 Pcs',
                'is_active' => true,
            ],
            
            // Latest Products
            [
                'item_name' => 'Red Bijili / சிவப்பு பிஜிலி',
                'quantity' => 180,
                'price' => 36.00,
                'original_price' => 140.00,
                'discount_percentage' => 74,
                'category' => 'BIJILI CRACKERS',
                'description' => '1 Pkt/100 Pcs',
                'is_active' => true,
            ],
            [
                'item_name' => 'Stripped Bijili / வரி பிஜிலி',
                'quantity' => 160,
                'price' => 41.00,
                'original_price' => 160.00,
                'discount_percentage' => 74,
                'category' => 'BIJILI CRACKERS',
                'description' => '1 Pkt/100 Pcs',
                'is_active' => true,
            ],
            [
                'item_name' => 'Kit Kat / கிட் கட்',
                'quantity' => 140,
                'price' => 31.00,
                'original_price' => 120.00,
                'discount_percentage' => 74,
                'category' => 'CHIT PUT',
                'description' => '1 Pkt/50 Pcs',
                'is_active' => true,
            ],
            [
                'item_name' => 'Dragon Fight / டிராகன் ஃபைட்',
                'quantity' => 90,
                'price' => 31.00,
                'original_price' => 120.00,
                'discount_percentage' => 74,
                'category' => 'CHIT PUT',
                'description' => '1 Pkt/50 Pcs',
                'is_active' => true,
            ],
            
            // Additional Products
            [
                'item_name' => '4" Twinkling Star / 4" சாட்டை',
                'quantity' => 110,
                'price' => 61.00,
                'original_price' => 240.00,
                'discount_percentage' => 75,
                'category' => 'TWINKLING STAR',
                'description' => '1 Pkt/20 Pcs',
                'is_active' => true,
            ],
            [
                'item_name' => '1 1/2" Twinkling Star / 1 1/2" சாட்டை',
                'quantity' => 130,
                'price' => 26.00,
                'original_price' => 100.00,
                'discount_percentage' => 74,
                'category' => 'TWINKLING STAR',
                'description' => '1 Pkt/25 Pcs',
                'is_active' => true,
            ],
            [
                'item_name' => '7cm Colour Sparklers / 7 செ.மீ கலர் கம்பி',
                'quantity' => 200,
                'price' => 12.00,
                'original_price' => 48.00,
                'discount_percentage' => 75,
                'category' => 'SPARKLERS',
                'description' => '1 Pkt/50 Pcs',
                'is_active' => true,
            ],
            [
                'item_name' => '7cm Electric Sparklers / 7 செ.மீ சாதா கம்பி',
                'quantity' => 180,
                'price' => 10.00,
                'original_price' => 40.00,
                'discount_percentage' => 75,
                'category' => 'SPARKLERS',
                'description' => '1 Pkt/50 Pcs',
                'is_active' => true,
            ],
            
            // Gift Boxes
            [
                'item_name' => '30 Items Vinayaga',
                'quantity' => 50,
                'price' => 459.00,
                'original_price' => 1800.00,
                'discount_percentage' => 75,
                'category' => 'GIFT BOX',
                'description' => 'Special Combo Pack',
                'is_active' => true,
            ],
            [
                'item_name' => '25 Item Spider Man',
                'quantity' => 40,
                'price' => 398.00,
                'original_price' => 1560.00,
                'discount_percentage' => 74,
                'category' => 'GIFT BOX',
                'description' => 'Special Combo Pack',
                'is_active' => true,
            ],
            [
                'item_name' => '40 Items Peacock',
                'quantity' => 30,
                'price' => 599.00,
                'original_price' => 2400.00,
                'discount_percentage' => 75,
                'category' => 'GIFT BOX',
                'description' => 'Special Combo Pack',
                'is_active' => true,
            ],
        ];

        foreach ($stocks as $stock) {
            Stock::create($stock);
        }
    }
} 