<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Order;

class OrderItemsTableSeeder extends Seeder
{
    public function run()
    {
        $order = Order::latest()->first();
        if ($order) {
            DB::table('order_items')->insert([
                'order_id' => $order->id,
                'product_name' => 'Sample Product',
                'content' => 'Box',
                'price' => 100.00,
                'quantity' => 2,
                'subtotal' => 200.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
} 