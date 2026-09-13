<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = \App\Models\Order::latest()->first();
echo "Order ID: " . $order->id . " Status: " . $order->status . "\n";

$updateData = [
    'status' => 'dispatched',
    'payment_status' => 'paid',
];
$order->update($updateData);
echo "After update Order ID: " . $order->id . " Status: " . $order->status . "\n";
