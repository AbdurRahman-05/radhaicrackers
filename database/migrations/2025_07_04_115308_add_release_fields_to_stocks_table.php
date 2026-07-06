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
        Schema::table('stocks', function (Blueprint $table) {
            if (!Schema::hasColumn('stocks', 'last_released_at')) {
                $table->timestamp('last_released_at')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('stocks', 'next_release_at')) {
                $table->timestamp('next_release_at')->nullable()->after('last_released_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            if (Schema::hasColumn('stocks', 'last_released_at')) {
                $table->dropColumn('last_released_at');
            }
            if (Schema::hasColumn('stocks', 'next_release_at')) {
                $table->dropColumn('next_release_at');
            }
        });
    }
};
