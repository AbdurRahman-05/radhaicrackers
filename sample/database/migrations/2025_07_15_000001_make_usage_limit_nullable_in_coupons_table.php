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
        // Set all coupons to be active and start in the past
        DB::table('coupons')->update([
            'is_active' => 1,
            'starts_at' => now()->subDay(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: cannot safely revert
    }
}; 