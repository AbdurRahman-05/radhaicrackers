<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$phone = '9087786231';
$token = 'ca4869c05587ab6e2c2052011dfa8190296a1c1d08a357f7d4a5f6e89e9568b7';
$phoneNoId = '747598631767762';

function testTemplate($name, $bodyParams, $token, $phoneNoId, $phone) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://waapi.automationclub.in/api/v2/whatsapp-business/messages',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode([
            'to' => '+91' . $phone,
            'phoneNoId' => $phoneNoId,
            'type' => 'template',
            'name' => $name,
            'language' => 'en_US',
            'bodyParams' => $bodyParams
        ]),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

echo "Testing template 'thanks_purchasing':\n";
echo testTemplate('thanks_purchasing', ['Customer', '₹1000.00', '1026'], $token, $phoneNoId, $phone) . "\n\n";

echo "Testing template 'payment_paid':\n";
echo testTemplate('payment_paid', ['Customer', '₹1000.00', '1026', 'http://example.com'], $token, $phoneNoId, $phone) . "\n\n";

echo "Testing template 'order_dispatched':\n";
echo testTemplate('order_dispatched', ['Customer', '1026', 'Lorry', 'TN58', 'Madurai'], $token, $phoneNoId, $phone) . "\n\n";
