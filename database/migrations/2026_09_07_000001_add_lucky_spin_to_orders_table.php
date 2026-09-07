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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'lucky_spin_prize')) {
                $table->string('lucky_spin_prize')->nullable()->after('coupon_discount');
            }
            if (!Schema::hasColumn('orders', 'lucky_spin_discount')) {
                $table->decimal('lucky_spin_discount', 10, 2)->default(0)->after('lucky_spin_prize');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'lucky_spin_prize')) {
                $table->dropColumn('lucky_spin_prize');
            }
            if (Schema::hasColumn('orders', 'lucky_spin_discount')) {
                $table->dropColumn('lucky_spin_discount');
            }
        });
    }
};
