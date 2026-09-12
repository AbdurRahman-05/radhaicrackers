<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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

            // Seed initial default slides using high quality 2026 banner images
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
                [
                    'title' => 'Mega Diwali Combo Packs',
                    'subtitle' => 'Curated festive family packages for non-stop celebrations',
                    'image' => 'images/radhe_crackers_images_2026/combo packs banner.png',
                    'link_url' => '/combos',
                    'button_text' => 'View Combos',
                    'sort_order' => 3,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
