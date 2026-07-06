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
            $table->string('customer_name')->nullable();
            $table->string('customer_mobile')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_state')->nullable();
            $table->string('customer_district')->nullable();
            $table->string('customer_city')->nullable();
            $table->string('delivery_point')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('verify_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'customer_mobile',
                'customer_email',
                'customer_state',
                'customer_district',
                'customer_city',
                'delivery_point',
                'pin_code',
                'coupon_code',
                'verify_code'
            ]);
        });
    }
};
