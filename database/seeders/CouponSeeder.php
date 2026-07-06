<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some sample products for bonus items
        $bonusProduct = Stock::where('is_active', true)->first();

        $coupons = [
            [
                'code' => 'ALWAYSACTIVE',
                'name' => 'Always Active Test Coupon',
                'description' => 'This coupon is always active for testing purposes',
                'type' => 'fixed_amount',
                'value' => 50,
                'minimum_order_amount' => 100,
                'usage_limit' => 10000,
                'user_limit' => 1000,
                'starts_at' => now()->subYear(),
                'expires_at' => null,
                'is_active' => true,
            ],
            [
                'code' => 'WELCOME20',
                'name' => 'Welcome Discount',
                'description' => 'Get 20% off on your first order',
                'type' => 'percentage',
                'value' => 20,
                'minimum_order_amount' => 500,
                'maximum_discount' => 1000,
                'usage_limit' => 100,
                'user_limit' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'FLAT100',
                'name' => 'Flat ₹100 Off',
                'description' => 'Get ₹100 off on orders above ₹1000',
                'type' => 'fixed_amount',
                'value' => 100,
                'minimum_order_amount' => 1000,
                'usage_limit' => 50,
                'user_limit' => 2,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(2),
                'is_active' => true,
            ],
            [
                'code' => 'BONUSGIFT',
                'name' => 'Free Bonus Item',
                'description' => 'Get a free bonus item with your order',
                'type' => 'bonus_items',
                'value' => 0,
                'minimum_order_amount' => 800,
                'bonus_product_id' => $bonusProduct ? $bonusProduct->id : null,
                'bonus_quantity' => 1,
                'usage_limit' => 30,
                'user_limit' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonth(),
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER50',
                'name' => 'Summer Sale',
                'description' => '50% off on summer collection',
                'type' => 'percentage',
                'value' => 50,
                'minimum_order_amount' => 2000,
                'maximum_discount' => 2000,
                'usage_limit' => 20,
                'user_limit' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addDays(30),
                'is_active' => true,
                'applies_to_categories' => ['Summer Collection', 'Festival'],
            ],
            [
                'code' => 'NEWUSER',
                'name' => 'New User Special',
                'description' => 'Special discount for new users',
                'type' => 'fixed_amount',
                'value' => 200,
                'minimum_order_amount' => 1500,
                'usage_limit' => 200,
                'user_limit' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }
    }
} 