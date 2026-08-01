<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$phone = '9087786231'; // test phone from screenshot

$smsService = new \App\Services\SMSService();

echo "Testing payment_paid...\n";
$res1 = $smsService->sendWhatsApp($phone, '', 'payment_paid', [
    'customer_name' => 'for testing purpose',
    'order_id' => '1024',
    'order_value' => '₹4,833.00',
    'invoice_url' => 'http://127.0.0.1:8000/user/orders/1024/invoice-pdf'
]);
echo "payment_paid result: " . ($res1 ? "SUCCESS" : "FAILED") . "\n";

echo "Testing order_dispatched...\n";
$res2 = $smsService->sendWhatsApp($phone, '', 'order_dispatched', [
    'customer_name' => 'for testing purpose',
    'order_id' => '1024',
    'transport_provider' => 'Lorry',
    'transport_details' => 'TN 5859',
    'delivery_point' => 'Annanagar, Madurai',
    'delivery_type' => 'delivery'
]);
echo "order_dispatched result: " . ($res2 ? "SUCCESS" : "FAILED") . "\n";
