<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected $apiKey;
    protected $senderId;
    protected $route;
    protected $baseUrl;
    protected $otpTemplateId;

    public function __construct()
    {
        $this->apiKey        = config('services.lionsms.api_key', 'dcd3c5c00112b83116657d7f656660a1');
        $this->senderId      = config('services.lionsms.sender_id', 'RADHTR');
        $this->route         = config('services.lionsms.route', '7');
        $this->baseUrl       = config('services.lionsms.base_url', 'https://msg.lionsms.com/api/smsapi');
        $this->otpTemplateId = config('services.lionsms.otp_template_id', '1107172187374253331');
    }

    public function sendOtp($phone, $otp, $context = 'Login')
    {
        $rawPhone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($rawPhone) === 12 && str_starts_with($rawPhone, '91')) {
            $rawPhone = substr($rawPhone, 2);
        } elseif (strlen($rawPhone) === 11 && str_starts_with($rawPhone, '0')) {
            $rawPhone = substr($rawPhone, 1);
        }

        // Use the exact message and template as provided
        $message = "Your OTP for Login {$otp} Please do not share this code with anyone for your security. -Radhe Traders";
        $params = [
            'key'        => $this->apiKey,
            'sender'     => $this->senderId,
            'number'     => $rawPhone,
            'route'      => 7, // Use route 7 for OTP as per user API
            'sms'        => $message,
            'templateid' => $this->otpTemplateId,
        ];

        try {
            $response = Http::timeout(10)->get($this->baseUrl, $params);

            Log::info('LionSMS API response', [
                'phone' => $rawPhone,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return true;
            } else {
                Log::error('LionSMS HTTP error', ['response' => $response->body()]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('LionSMS Exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function sendWhatsApp($phone, $otp, $context=null, $data=[])
    {
        $phone = preg_replace('/[^0-9]/', '', (string)$phone);
        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2);
        } elseif (strlen($phone) === 11 && str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }
        if (strlen($phone) === 10) {
            $phone = '91' . $phone;
        }

        if ($context === 'order_confirmation' && !empty($data)) {
            $name = $data['customer_name'] ?? "Customer"; 
            $order_value = $data['order_value'] ?? "₹0.00"; 
            $order_id = (string)($data['order_id'] ?? "0");

            try {
                $order = \App\Models\Order::with(['user', 'payment', 'logs'])->find($order_id);
                if ($order) {
                    $actualVal = (float)($order->total_amount ?: ($order->total ?: ($order->final_amount ?: 0)));
                    if ($actualVal > 0) {
                        $order_value = '₹' . number_format($actualVal, 2);
                    }

                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.user-order-invoice', compact('order'))->setPaper('a4', 'portrait');
                    $pdfContent = $pdf->output();
                    
                    $pdfFilename = "invoices/bill_{$order_id}_" . time() . ".pdf";
                    \Illuminate\Support\Facades\Storage::disk('public')->put($pdfFilename, $pdfContent);
                    
                    $pdfUrl = route('public.pdf_invoice', $order_id);
                    $appUrl = rtrim(config('app.url'), '/');
                    if (str_contains($pdfUrl, 'localhost') || str_contains($pdfUrl, '127.0.0.1')) {
                        $pdfUrl = "https://radhecrackers.com/public-pdf/{$order_id}";
                    }
                    $pdfUrl .= (str_contains($pdfUrl, '?') ? '&' : '?') . 'v=' . time();

                    // Send Meta Approved Document Template (order_bill_pdf) - ONLY for 1st message
                    $docCurl = curl_init();
                    curl_setopt_array($docCurl, array(
                        CURLOPT_URL => 'https://waapi.automationclub.in/api/integration/whatsapp-message/747598631767762/messages',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => json_encode([
                            'messaging_product' => 'whatsapp',
                            'recipient_type' => 'individual',
                            'to' => $phone,
                            'type' => 'template',
                            'template' => [
                                'name' => 'order_bill_pdf',
                                'language' => ['code' => 'en_US'],
                                'components' => [
                                    [
                                        'type' => 'header',
                                        'parameters' => [
                                            [
                                                'type' => 'document',
                                                'document' => [
                                                    'link' => $pdfUrl,
                                                    'filename' => "Radhe_Crackers_Order_#{$order_id}.pdf"
                                                ]
                                            ]
                                        ]
                                    ],
                                    [
                                        'type' => 'body',
                                        'parameters' => [
                                            ['type' => 'text', 'text' => $name],
                                            ['type' => 'text', 'text' => $order_id],
                                            ['type' => 'text', 'text' => $order_value]
                                        ]
                                    ]
                                ]
                            ]
                        ]),
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Bearer dJEFvrN8T-RhN7XprIFXUcgBNOCfG-ru9rDjhVLAT0P3jO_b2YGd9SEz23thnAok',
                            'Content-Type: application/json'
                        ),
                    ));
                    $docResponse = curl_exec($docCurl);
                    curl_close($docCurl);
                    Log::info('WhatsApp Approved Document Template (order_bill_pdf) sent for order_confirmation', ['phone' => $phone, 'pdf_url' => $pdfUrl, 'response' => $docResponse]);

                    return true;
                }
            } catch (\Exception $e) {
                Log::error('WhatsApp Order Confirmation Exception', ['error' => $e->getMessage()]);
                return false;
            }
        } elseif ($context === 'payment_paid' && !empty($data)) {
            $name = $data['customer_name'] ?? "Customer"; 
            $order_value = $data['order_value'] ?? "₹0.00"; 
            $order_id = (string)($data['order_id'] ?? "0");
            $param3 = "Order #{$order_id}. ";

            try {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://waapi.automationclub.in/api/integration/whatsapp-message/747598631767762/messages',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode([
                        'messaging_product' => 'whatsapp',
                        'recipient_type' => 'individual',
                        'to' => $phone,
                        'type' => 'template',
                        'template' => [
                            'name' => 'second_msg',
                            'language' => ['code' => 'en_US'],
                            'components' => [
                                [
                                    'type' => 'body',
                                    'parameters' => [
                                        ['type' => 'text', 'text' => $name],
                                        ['type' => 'text', 'text' => "#{$order_id}"],
                                        ['type' => 'text', 'text' => "{$order_value} "]
                                    ]
                                ]
                            ]
                        ]
                    ]),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer dJEFvrN8T-RhN7XprIFXUcgBNOCfG-ru9rDjhVLAT0P3jO_b2YGd9SEz23thnAok',
                        'Content-Type: application/json'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                Log::info('WhatsApp payment_paid (second_msg) template sent', ['phone' => $phone, 'response' => $response]);
                return true;
            } catch (\Exception $e) {
                Log::error('WhatsApp Payment Paid Exception', ['error' => $e->getMessage()]);
                return false;
            }
        } elseif ($context === 'order_dispatched' && !empty($data)) {
            $name = $data['customer_name'] ?? "Customer"; 
            $order_id = (string)($data['order_id'] ?? "0");
            $provider = $data['transport_provider'] ?? '';
            $details = $data['transport_details'] ?? '';
            $order_value = $data['order_value'] ?? "₹0.00";
            $param3 = ($provider ?: 'Lorry Transport') . ($details ? " ({$details})" : '');

            try {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://waapi.automationclub.in/api/integration/whatsapp-message/747598631767762/messages',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode([
                        'messaging_product' => 'whatsapp',
                        'recipient_type' => 'individual',
                        'to' => $phone,
                        'type' => 'template',
                        'template' => [
                            'name' => '3_message',
                            'language' => ['code' => 'en_US'],
                            'components' => [
                                [
                                    'type' => 'body',
                                    'parameters' => [
                                        ['type' => 'text', 'text' => $name],
                                        ['type' => 'text', 'text' => "#{$order_id}"],
                                        ['type' => 'text', 'text' => $param3]
                                    ]
                                ]
                            ]
                        ]
                    ]),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer dJEFvrN8T-RhN7XprIFXUcgBNOCfG-ru9rDjhVLAT0P3jO_b2YGd9SEz23thnAok',
                        'Content-Type: application/json'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                Log::info('WhatsApp order_dispatched (3_message) template sent', ['phone' => $phone, 'response' => $response]);
                return true;
            } catch (\Exception $e) {
                Log::error('WhatsApp Order Dispatched Exception', ['error' => $e->getMessage()]);
                return false;
            }
        } else {
            if ($context === 'otp' && !empty($otp)) {
                try {
                    $otpStr = (string)$otp;
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => "https://waapi.automationclub.in/api/integration/whatsapp-message/747598631767762/messages",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => json_encode([
                            'messaging_product' => 'whatsapp',
                            'recipient_type' => 'individual',
                            'to' => $phone,
                            'type' => 'template',
                            'template' => [
                                'name' => 'otp',
                                'language' => ['code' => 'en_GB'],
                                'components' => [
                                    [
                                        'type' => 'body',
                                        'parameters' => [['type' => 'text', 'text' => $otpStr]]
                                    ],
                                    [
                                        'type' => 'button',
                                        'sub_type' => 'url',
                                        'index' => 0,
                                        'parameters' => [['type' => 'text', 'text' => $otpStr]]
                                    ]
                                ]
                            ]
                        ]),
                        CURLOPT_HTTPHEADER => [
                            "Accept: application/json",
                            "Authorization: Bearer dJEFvrN8T-RhN7XprIFXUcgBNOCfG-ru9rDjhVLAT0P3jO_b2YGd9SEz23thnAok",
                            "Content-Type: application/json",
                        ],
                    ]);
                    $response = curl_exec($curl);
                    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    $err = curl_error($curl);
                    curl_close($curl);

                    $resData = json_decode($response, true);
                    $isSuccess = (!$err && $httpCode >= 200 && $httpCode < 300 && !empty($resData['messages']));

                    if ($isSuccess) {
                        Log::info('WhatsApp OTP successfully dispatched', [
                            'phone' => $phone,
                            'http_code' => $httpCode,
                            'msg_id' => $resData['messages'][0]['id'] ?? null
                        ]);
                    } else {
                        Log::error('WhatsApp OTP dispatch failed', [
                            'phone' => $phone,
                            'http_code' => $httpCode,
                            'response' => $response,
                            'error' => $err
                        ]);
                    }

                    return $isSuccess;
                } catch (\Exception $e) {
                    Log::error('WhatsApp OTP Exception', ['error' => $e->getMessage()]);
                    return false;
                }
            }
        }
        return false;
    }

    public function sendWhatsAppAdmin($phone, $otp, $context=null, $data=[])
    {
        if ($context === 'order_confirmation' && !empty($data)) {
            $template_name = "neworder";
            $order_value = (string)($data['order_value'] ?? "₹0.00"); 
            $order_id = (string)($data['order_id'] ?? "0");
            $custPhone = (string)$phone;
            
            // Format customer contact display (10 digits) for template parameter 3
            $rawCustPhone = preg_replace('/[^0-9]/', '', $custPhone);
            if (strlen($rawCustPhone) === 12 && str_starts_with($rawCustPhone, '91')) {
                $contactDisplay = substr($rawCustPhone, 2);
            } else {
                $contactDisplay = $rawCustPhone;
            }

            // Primary Admin number to receive lead notifications (single recipient)
            $adminNumbers = ['919943930432'];
            
            // Check dynamic database settings for whatsapp business number
            try {
                $settingPhone = \DB::table('settings')->where('key', 'whatsapp_business_number')->value('value') 
                             ?? \DB::table('settings')->where('key', 'business_phone')->value('value');
                if ($settingPhone) {
                    $cleanSettingPhone = preg_replace('/[^0-9]/', '', $settingPhone);
                    if (strlen($cleanSettingPhone) === 10) {
                        $cleanSettingPhone = '91' . $cleanSettingPhone;
                    }
                    // Exclude sender's own number (8807060809) and excluded number (9751048974)
                    if (strlen($cleanSettingPhone) === 12 
                        && $cleanSettingPhone !== '918807060809' 
                        && $cleanSettingPhone !== '919751048974' 
                        && !in_array($cleanSettingPhone, $adminNumbers)) {
                        $adminNumbers[] = $cleanSettingPhone;
                    }
                }
            } catch (\Exception $settingEx) {
                // Ignore DB error if table is unavailable
            }

            // Idempotency: Prevent duplicate admin notifications for the same order
            $cacheKey = "admin_wa_lead_sent_order_{$order_id}";
            if (!empty($order_id) && \Illuminate\Support\Facades\Cache::has($cacheKey)) {
                Log::info("WhatsApp Admin Lead Message already dispatched for Order #{$order_id}, skipping duplicate execution.");
                return true;
            }

            $successCount = 0;

            foreach (array_unique($adminNumbers) as $adminPhone) {
                $sentSuccessfully = false;

                // 1. Send via Meta Integration API (Primary Endpoint)
                try {
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => 'https://waapi.automationclub.in/api/integration/whatsapp-message/747598631767762/messages',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_TIMEOUT => 20,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => json_encode([
                            'messaging_product' => 'whatsapp',
                            'recipient_type' => 'individual',
                            'to' => $adminPhone,
                            'type' => 'template',
                            'template' => [
                                'name' => $template_name,
                                'language' => ['code' => 'en_US'],
                                'components' => [
                                    [
                                        'type' => 'body',
                                        'parameters' => [
                                            ['type' => 'text', 'text' => $order_id],
                                            ['type' => 'text', 'text' => $order_value],
                                            ['type' => 'text', 'text' => $contactDisplay]
                                        ]
                                    ]
                                ]
                            ]
                        ]),
                        CURLOPT_HTTPHEADER => [
                            'Authorization: Bearer dJEFvrN8T-RhN7XprIFXUcgBNOCfG-ru9rDjhVLAT0P3jO_b2YGd9SEz23thnAok',
                            'Content-Type: application/json'
                        ],
                    ]);
                    $response = curl_exec($curl);
                    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    $err = curl_error($curl);
                    curl_close($curl);

                    Log::info('WhatsApp Admin Lead Message Meta Response', [
                        'admin_phone' => $adminPhone,
                        'order_id' => $order_id,
                        'http_code' => $httpCode,
                        'response' => $response,
                        'error' => $err
                    ]);

                    if (!$err && $httpCode >= 200 && $httpCode < 300) {
                        $metaData = json_decode($response, true);
                        if (is_array($metaData) && !empty($metaData['messages'])) {
                            $sentSuccessfully = true;
                            $successCount++;
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('WhatsApp Admin Lead Message Integration Exception', ['error' => $e->getMessage()]);
                }

                // 2. Fallback to legacy v2 endpoint ONLY IF primary Meta endpoint failed
                if (!$sentSuccessfully) {
                    try {
                        $curl2 = curl_init();
                        curl_setopt_array($curl2, [
                            CURLOPT_URL => 'https://waapi.automationclub.in/api/v2/whatsapp-business/messages',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 10,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode([
                                'to' => $adminPhone,
                                'phoneNoId' => '747598631767762',
                                'type' => 'template',
                                'name' => $template_name,
                                'language' => 'en_US',
                                'bodyParams' => [$order_id, $order_value, $contactDisplay]
                            ]),
                            CURLOPT_HTTPHEADER => [
                                'Authorization: Bearer ca4869c05587ab6e2c2052011dfa8190296a1c1d08a357f7d4a5f6e89e9568b7',
                                'Content-Type: application/json'
                            ],
                        ]);
                        $response2 = curl_exec($curl2);
                        $httpCode2 = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
                        $err2 = curl_error($curl2);
                        curl_close($curl2);

                        $resData2 = json_decode($response2, true);
                        if (!$err2 && $httpCode2 >= 200 && $httpCode2 < 300 && (!empty($resData2['id']) || !empty($resData2['messages']))) {
                            $successCount++;
                        }

                        Log::info('WhatsApp Admin Lead Message v2 Backup Response', [
                            'admin_phone' => $adminPhone,
                            'http_code' => $httpCode2,
                            'response' => $response2,
                            'error' => $err2
                        ]);
                    } catch (\Exception $e2) {
                        Log::error('WhatsApp Admin Lead Message v2 Backup Exception', ['error' => $e2->getMessage()]);
                    }
                }
            }

            if ($successCount > 0 && !empty($order_id)) {
                \Illuminate\Support\Facades\Cache::put($cacheKey, true, now()->addHours(12));
            }

            return $successCount > 0;
        }
        return false;
    }

    private function getPublicPdfUrl($pdfFilename, $pdfContent)
    {
        $appUrl = rtrim(config('app.url'), '/');
        if ($appUrl && !str_contains($appUrl, 'localhost') && !str_contains($appUrl, '127.0.0.1')) {
            $pdfUrl = $appUrl . '/storage/' . $pdfFilename;
            $ch = curl_init($pdfUrl);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($code >= 200 && $code < 300) {
                return $pdfUrl;
            }
        }

        try {
            $tmpPath = sys_get_temp_dir() . '/' . basename($pdfFilename);
            file_put_contents($tmpPath, $pdfContent);
            
            $cfile = new \CURLFile($tmpPath, 'application/pdf', basename($pdfFilename));
            $ch = curl_init('https://tmpfiles.org/api/v1/upload');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => ['file' => $cfile],
                CURLOPT_TIMEOUT => 15
            ]);
            $res = curl_exec($ch);
            curl_close($ch);
            @unlink($tmpPath);

            $json = json_decode($res, true);
            if (isset($json['data']['url'])) {
                return str_replace('tmpfiles.org/', 'tmpfiles.org/dl/', $json['data']['url']);
            }
        } catch (\Exception $e) {
            Log::error('Public PDF upload error', ['msg' => $e->getMessage()]);
        }

        return ($appUrl ?: 'https://mediumspringgreen-dragonfly-181890.hostingersite.com') . '/storage/' . $pdfFilename;
    }
}