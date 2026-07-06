<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Stock;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        \App\Models\User::firstOrCreate(
            [
                'email' => 'admin@radhecrackers.com',
            ],
            [
                'name' => 'Admin',
                'phone' => '9999999999',
                'password' => Hash::make('admin123'),
                'is_admin' => 1,
                'is_active' => 1,
            ]
        );

        // Seed stock data
        $this->call([
            StockSeeder::class,
            RolePermissionSeeder::class,
        ]);
    }
}
