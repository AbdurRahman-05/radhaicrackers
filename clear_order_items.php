<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Clearing old order_items data...\n";

try {
    // Clear all order_items records
    DB::table('order_items')->truncate();
    echo "✓ Cleared all order_items records\n";
    
    // Check if total_amount column exists
    $columns = DB::select("SHOW COLUMNS FROM orders LIKE 'total_amount'");
    if (count($columns) > 0) {
        echo "✓ total_amount column exists in orders table\n";
    } else {
        echo "✗ total_amount column does not exist in orders table\n";
    }
    
    // Check if items_json column exists
    $columns = DB::select("SHOW COLUMNS FROM orders LIKE 'items_json'");
    if (count($columns) > 0) {
        echo "✓ items_json column exists in orders table\n";
    } else {
        echo "✗ items_json column does not exist in orders table\n";
    }
    
    echo "\nSystem is ready for new orders with JSON-based items storage!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 