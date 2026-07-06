<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $firstUser = DB::table('users')->orderBy('id')->first();
        if ($firstUser) {
            DB::table('orders')->update(['user_id' => $firstUser->id]);
        }
    }

    public function down(): void
    {
        // No rollback for this data migration
    }
};
