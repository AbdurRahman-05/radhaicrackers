<?php

$mobile = "918248550502";
$purpose = "login";
$otp = "1234";

$message = "Your OTP for $purpose $otp Please do not share this code with anyone for your security.";

$params = [
    'key'         => 'dcd3c5c00112b83116657d7f656660a1',
    'sender'      => 'RADHTR',
    'number'      => $mobile,
    'route'       => '9',
    'sms'         => $message,
    'templateid'  => '1107172187374253331',
];

$url = "https://msg.lionsms.com/api/smsapi?" . http_build_query($params);

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false, // For testing only. Do NOT use in production
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/x-www-form-urlencoded"
    ]
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    echo "LionSMS Response: " . $response;
}

curl_close($ch);
