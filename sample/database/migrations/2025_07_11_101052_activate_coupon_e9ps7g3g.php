<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ActivateCouponE9PS7G3G extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update all coupons to be active, start today, no end date
        DB::table('coupons')
            ->where('code', 'E9PS7G3G') // Remove this line to apply to ALL coupons
            ->update([
                'is_active' => 1,
                'start_date' => now()->toDateString(),
                'end_date' => null,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Optionally, you can revert the changes here if needed
        // For example, set is_active to 0 for this coupon
        DB::table('coupons')
            ->where('code', 'E9PS7G3G')
            ->update([
                'is_active' => 0,
            ]);
    }
} 