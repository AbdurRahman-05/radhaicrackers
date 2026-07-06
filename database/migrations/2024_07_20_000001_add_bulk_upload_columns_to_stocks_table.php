<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {
            if (!Schema::hasColumn('stocks', 'special_discount_percentage')) {
                $table->integer('special_discount_percentage')->nullable()->after('discount_percentage');
            }
            if (!Schema::hasColumn('stocks', 'is_popular')) {
                $table->boolean('is_popular')->default(0)->after('show_on_shop');
            }
            if (!Schema::hasColumn('stocks', 'is_latest')) {
                $table->boolean('is_latest')->default(0)->after('is_popular');
            }
        });
    }

    public function down()
    {
        Schema::table('stocks', function (Blueprint $table) {
            if (Schema::hasColumn('stocks', 'special_discount_percentage')) {
                $table->dropColumn('special_discount_percentage');
            }
            if (Schema::hasColumn('stocks', 'is_popular')) {
                $table->dropColumn('is_popular');
            }
            if (Schema::hasColumn('stocks', 'is_latest')) {
                $table->dropColumn('is_latest');
            }
        });
    }
}; 