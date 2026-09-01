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
            if (!Schema::hasColumn('stocks', 'meta_title')) {
                $table->string('meta_title', 255)->nullable()->after('description');
            }
            if (!Schema::hasColumn('stocks', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('stocks', 'meta_keywords')) {
                $table->text('meta_keywords')->nullable()->after('meta_description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('stocks', 'meta_keywords')) {
                $columns[] = 'meta_keywords';
            }
            if (Schema::hasColumn('stocks', 'meta_description')) {
                $columns[] = 'meta_description';
            }
            if (Schema::hasColumn('stocks', 'meta_title')) {
                $columns[] = 'meta_title';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
