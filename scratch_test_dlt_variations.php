<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = 'dcd3c5c00112b83116657d7f656660a1';
$phone = '9087786231';
$otp = '765432';

$templates = [
    [
        'sender' => 'RADHTR',
        'template_id' => '1107172187374253331',
        'msg' => "Your OTP for login is {$otp}. Radhe Traders"
    ],
    [
        'sender' => 'RADHTR',
        'template_id' => '1107172187374253331',
        'msg' => "Your OTP is {$otp}. Radhe Traders"
    ],
    [
        'sender' => 'RADHTR',
        'template_id' => '1107172187374253331',
        'msg' => "Your OTP for login is {$otp}. Valid for 10 minutes. Radhe Crackers"
    ],
    [
        'sender' => 'RADHTR',
        'template_id' => '1107172187374253331',
        'msg' => "Your OTP for Cracker Shop login is {$otp}."
    ],
];

foreach ($templates as $idx => $item) {
    $res = Http::get('https://msg.lionsms.com/api/smsapi', [
        'api_key' => $apiKey,
        'type' => 'text',
        'contacts' => $phone,
        'senderid' => $item['sender'],
        'msg' => $item['msg'],
        'template_id' => $item['template_id'],
    ]);

    echo "Template #{$idx} Status: " . $res->status() . " | Body: " . $res->body() . "\n";
}
