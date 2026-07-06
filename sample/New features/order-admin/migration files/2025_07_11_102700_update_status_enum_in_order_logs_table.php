<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE order_logs MODIFY status ENUM('pending','confirmed','dispatched','completed','payment_status_changed','notes_updated')");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE order_logs MODIFY status ENUM('pending','confirmed','dispatched','completed')");
    }
};
