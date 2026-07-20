<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$takeawayPoints = App\Models\Order::where('delivery_point', 'like', '%takeaway%')
    ->orWhere('delivery_point', 'like', '%shop%')
    ->orWhere('notes', 'like', '%takeaway%')
    ->orWhere('notes', 'like', '%shop%')
    ->count();

echo "Orders with 'takeaway' or 'shop' in delivery_point or notes: {$takeawayPoints}\n";
