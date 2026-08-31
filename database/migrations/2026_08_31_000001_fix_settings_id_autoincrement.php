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
        if (Schema::hasTable('settings')) {
            try {
                DB::statement("ALTER TABLE `settings` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
            } catch (\Exception $e) {
                // Fallback using Schema builder
                try {
                    Schema::table('settings', function (Blueprint $table) {
                        $table->bigIncrements('id')->change();
                    });
                } catch (\Exception $ex) {
                    // Ignore if already auto-increment
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
