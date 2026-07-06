<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure columns exist before updating
        Schema::table('coupons', function (Blueprint $table) {
            if (!Schema::hasColumn('coupons', 'start_date')) {
                $table->date('start_date')->nullable();
            }
            if (!Schema::hasColumn('coupons', 'end_date')) {
                $table->date('end_date')->nullable();
            }
        });

        // Now perform the update
        DB::table('coupons')->where('code', 'E9PS7G3G')->update([
            'is_active' => 1,
            'start_date' => '2025-07-15',
            'end_date' => null,
        ]);
    }

    public function down(): void
    {
        // Optionally drop the columns if you want
        Schema::table('coupons', function (Blueprint $table) {
            if (Schema::hasColumn('coupons', 'start_date')) {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('coupons', 'end_date')) {
                $table->dropColumn('end_date');
            }
        });
    }
}; 