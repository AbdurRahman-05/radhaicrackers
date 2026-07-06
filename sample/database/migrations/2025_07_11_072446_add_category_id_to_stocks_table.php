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
        Schema::table('stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('category');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->index('category_id');
        });

        // Migrate existing category data
        $this->migrateExistingCategories();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropIndex(['category_id']);
            $table->dropColumn('category_id');
        });
    }

    /**
     * Migrate existing category strings to category IDs
     */
    private function migrateExistingCategories()
    {
        // Get unique categories from stocks table
        $existingCategories = DB::table('stocks')
            ->select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        // Create categories for each unique category name
        foreach ($existingCategories as $categoryName) {
            $categoryId = DB::table('categories')->insertGetId([
                'name' => $categoryName,
                'slug' => \Illuminate\Support\Str::slug($categoryName),
                'is_active' => true,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update stocks with this category name to use the new category_id
            DB::table('stocks')
                ->where('category', $categoryName)
                ->update(['category_id' => $categoryId]);
        }
    }
};
