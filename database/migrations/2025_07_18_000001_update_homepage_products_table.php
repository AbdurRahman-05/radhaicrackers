<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('homepage_products', function (Blueprint $table) {
            if (Schema::hasColumn('homepage_products', 'type')) {
                $table->dropColumn('type');
            }
            if (!Schema::hasColumn('homepage_products', 'is_popular')) {
                $table->boolean('is_popular')->default(false);
            }
            if (!Schema::hasColumn('homepage_products', 'is_latest')) {
                $table->boolean('is_latest')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homepage_products', function (Blueprint $table) {
            if (Schema::hasColumn('homepage_products', 'is_popular')) {
                $table->dropColumn('is_popular');
            }
            if (Schema::hasColumn('homepage_products', 'is_latest')) {
                $table->dropColumn('is_latest');
            }
            if (!Schema::hasColumn('homepage_products', 'type')) {
                $table->string('type')->nullable();
            }
        });
    }
}; 