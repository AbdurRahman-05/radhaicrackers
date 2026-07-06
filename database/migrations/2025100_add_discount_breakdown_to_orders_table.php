<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('discount_70_percent', 10, 2)->default(0.00)->after('subtotal');
            $table->decimal('amount_after_70_discount', 10, 2)->nullable()->after('discount_70_percent');
            $table->decimal('special_discount_15_percent', 10, 2)->default(0.00)->after('amount_after_70_discount');
            $table->decimal('amount_after_15_discount', 10, 2)->nullable()->after('special_discount_15_percent');
            $table->decimal('packing_charge_5_percent', 10, 2)->default(0.00)->after('amount_after_15_discount');
            $table->decimal('coupon_discount', 10, 2)->default(0.00)->after('packing_charge_5_percent');
            $table->decimal('final_amount', 10, 2)->nullable()->after('coupon_discount');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'discount_70_percent',
                'amount_after_70_discount',
                'special_discount_15_percent',
                'amount_after_15_discount',
                'packing_charge_5_percent',
                'coupon_discount',
                'final_amount'
            ]);
        });
    }
}; 