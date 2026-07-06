<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->enum('type', ['percentage', 'fixed_amount', 'bonus_items'])->default('percentage')->change();
        });
    }

    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('type')->change();
        });
    }
}; 