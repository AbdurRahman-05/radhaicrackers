<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = 'dcd3c5c00112b83116657d7f656660a1';
$phone = '9087786231';
$otp = '882190';
$templateId = '1107172187374253331';

$senderIds = ['RADHTR', 'RDHCRK'];
$routes = ['1', '2', '4', '9', 'service', 'transactional', 'otp'];

$messages = [
    "Your OTP for login is {$otp}. Radhe Traders",
    "Your OTP is {$otp} - Radhe Traders",
    "Your OTP for login is: {$otp}. Valid for 10 minutes. Radhe Crackers",
];

foreach ($senderIds as $senderId) {
    foreach ($routes as $route) {
        foreach ($messages as $idx => $msg) {
            $url = "https://msg.lionsms.com/api/smsapi";
            $res = Http::get($url, [
                'api_key' => $apiKey,
                'type' => 'text',
                'contacts' => $phone,
                'senderid' => $senderId,
                'msg' => $msg,
                'template_id' => $templateId,
                'route' => $route,
            ]);

            echo "Sender: {$senderId} | Route: {$route} | Msg #{$idx} | Status: " . $res->status() . " | Body: " . $res->body() . "\n";
        }
    }
}
