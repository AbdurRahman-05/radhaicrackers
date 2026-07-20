<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DB CHECK CODES ===\n";
echo "Confirmed 2025: " . App\Models\Order::whereYear('created_at', 2025)->where('status', 'confirmed')->count() . "\n";
echo "Dispatched 2025: " . App\Models\Order::whereYear('created_at', 2025)->where('status', 'dispatched')->count() . "\n";
echo "Completed 2025: " . App\Models\Order::whereYear('created_at', 2025)->where('status', 'completed')->count() . "\n";
echo "Pending 2025: " . App\Models\Order::whereYear('created_at', 2025)->where('status', 'pending')->count() . "\n";
echo "Cancelled 2025: " . App\Models\Order::whereYear('created_at', 2025)->where('status', 'cancelled')->count() . "\n";

echo "Paid 2025: " . App\Models\Order::whereYear('created_at', 2025)->where('payment_status', 'paid')->count() . "\n";
echo "Payment Pending 2025: " . App\Models\Order::whereYear('created_at', 2025)->where('payment_status', 'pending')->count() . "\n";
