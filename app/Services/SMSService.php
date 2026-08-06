<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.lionsms.api_key') ?: config('services.sms.key');
    }

    public function sendOTP($phone, $otp, $context = 'Login')
    {
        $rawPhone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($rawPhone) === 12 && str_starts_with($rawPhone, '91')) {
            $rawPhone = substr($rawPhone, 2);
        }

        $baseUrl    = config('services.lionsms.base_url', 'https://msg.lionsms.com/api/smsapi');
        $apiKey     = config('services.lionsms.api_key', 'dcd3c5c00112b83116657d7f656660a1');
        $senderId   = config('services.lionsms.sender_id', 'RADHTR');
        $templateId = config('services.lionsms.otp_template_id', '1107172187374253331');

        // Exact DLT registered message pattern for Radhe Traders
        $message = "Your OTP for Login {$otp} Please do not share this code with anyone for your security. -Radhe Traders";

        $params = [
            'key'        => $apiKey,
            'sender'     => $senderId,
            'number'     => $rawPhone,
            'route'      => 7, // Route 7 is the dedicated transactional DLT OTP route
            'sms'        => $message,
            'templateid' => $templateId,
        ];

        try {
            $response = Http::timeout(10)->get($baseUrl, $params);

            Log::info('LionSMS API response', [
                'phone' => $rawPhone,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('LionSMS Exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function sendWhatsApp($phone, $otp, $context=null, $data=[])
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) === 10) {
            $phone = '91' . $phone;
        }

        if ($context === 'payment_paid' && !empty($data)) {
            $template_name = "thanks_purchasing";
            $name = $data['customer_name'] ?? "Customer"; 
            $order_id = (string)($data['order_id'] ?? "0");
            $order_value = ($data['order_value'] ?? "₹0.00") . " - PAYMENT PAID ✅"; 
            $bodyParams = [$name, $order_value, $order_id];

            try {
                // 1. Send main text template message
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://waapi.automationclub.in/api/v2/whatsapp-business/messages',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 10,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode([
                        'to' => $phone,
                        'phoneNoId' => '747598631767762',
                        'type' => 'template',
                        'name' => $template_name,
                        'language' => 'en_US',
                        'bodyParams' => $bodyParams
                    ]),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer ca4869c05587ab6e2c2052011dfa8190296a1c1d08a357f7d4a5f6e89e9568b7',
                        'Content-Type: application/json'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                Log::info('WhatsApp Payment Paid template sent', ['phone' => $phone, 'response' => $response]);

                // 2. Generate PDF bill and send as WhatsApp document attachment
                try {
                    $order = \App\Models\Order::with(['user', 'payment', 'logs'])->find($order_id);
                    if ($order) {
                        // Generate PDF binary in memory
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.user-order-invoice', compact('order'))->setPaper('a4', 'portrait');
                        $pdfContent = $pdf->output();
                        
                        // Save PDF to public storage for WhatsApp API to fetch
                        $pdfFilename = "invoices/bill_{$order_id}_" . time() . ".pdf";
                        \Illuminate\Support\Facades\Storage::disk('public')->put($pdfFilename, $pdfContent);
                        
                        $appUrl = rtrim(config('app.url'), '/');
                        if (!$appUrl || str_contains($appUrl, 'localhost') || str_contains($appUrl, '127.0.0.1')) {
                            $appUrl = 'https://mediumspringgreen-dragonfly-181890.hostingersite.com';
                        }
                        $pdfUrl = $appUrl . '/storage/' . $pdfFilename;

                        // Send document via WhatsApp integration API
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
                                'type' => 'document',
                                'document' => [
                                    'link' => $pdfUrl,
                                    'filename' => "Radhe_Crackers_Bill_#{$order_id}.pdf",
                                    'caption' => "📄 Radhe Crackers - Order Bill #{$order_id} - PAYMENT CONFIRMED ✅"
                                ]
                            ]),
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: Bearer dJEFvrN8T-RhN7XprIFXUcgBNOCfG-ru9rDjhVLAT0P3jO_b2YGd9SEz23thnAok',
                                'Content-Type: application/json'
                            ),
                        ));
                        $docResponse = curl_exec($docCurl);
                        curl_close($docCurl);
                        Log::info('WhatsApp PDF Bill sent', ['phone' => $phone, 'pdf_url' => $pdfUrl, 'response' => $docResponse]);
                    }
                } catch (\Exception $pdfEx) {
                    Log::error('WhatsApp PDF generation/send error', ['error' => $pdfEx->getMessage()]);
                }

                return true;
            } catch (\Exception $e) {
                Log::error('WhatsApp Exception', ['error' => $e->getMessage()]);
                return false;
            }
        } elseif ($context === 'order_dispatched' && !empty($data)) {
            $template_name = "thanks_purchasing";
            $name = $data['customer_name'] ?? "Customer"; 
            $order_id = (string)($data['order_id'] ?? "0");
            $provider = $data['transport_provider'] ?? "Lorry Transport";
            $details = $data['transport_details'] ?? "Assigned";
            $order_value = ($data['order_value'] ?? "₹0.00") . " - DISPATCHED 🚚 ({$provider} {$details})";
            $bodyParams = [$name, $order_value, $order_id];

            try {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://waapi.automationclub.in/api/v2/whatsapp-business/messages',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 10,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode([
                        'to' => $phone,
                        'phoneNoId' => '747598631767762',
                        'type' => 'template',
                        'name' => $template_name,
                        'language' => 'en_US',
                        'bodyParams' => $bodyParams
                    ]),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer ca4869c05587ab6e2c2052011dfa8190296a1c1d08a357f7d4a5f6e89e9568b7',
                        'Content-Type: application/json'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                Log::info('WhatsApp Order Dispatched sent via thanks_purchasing', ['phone' => $phone, 'response' => $response]);
                return true;
            } catch (\Exception $e) {
                Log::error('WhatsApp Exception', ['error' => $e->getMessage()]);
                return false;
            }
        } elseif ($context === 'order_confirmation' && !empty($data)) {
            $name = $data['customer_name'] ?? "Customer"; 
            $order_value = $data['order_value'] ?? "₹0.00"; 
            $order_id = (string)($data['order_id'] ?? "0");

            try {
                $order = \App\Models\Order::with(['user', 'payment', 'logs'])->find($order_id);
                if ($order) {
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.user-order-invoice', compact('order'))->setPaper('a4', 'portrait');
                    $pdfContent = $pdf->output();
                    
                    $pdfFilename = "invoices/bill_{$order_id}_" . time() . ".pdf";
                    \Illuminate\Support\Facades\Storage::disk('public')->put($pdfFilename, $pdfContent);
                    
                    $pdfUrl = route('public.pdf_invoice', $order_id);
                    $appUrl = rtrim(config('app.url'), '/');
                    if (str_contains($pdfUrl, 'localhost') || str_contains($pdfUrl, '127.0.0.1')) {
                        $pdfUrl = "https://mediumspringgreen-dragonfly-181890.hostingersite.com/public-pdf/{$order_id}";
                    }

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
                                        ['type' => 'text', 'text' => $order_value],
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
                                        'parameters' => [['type' => 'text', 'text' => $otp]]
                                    ],
                                    [
                                        'type' => 'button',
                                        'sub_type' => 'url',
                                        'index' => '0',
                                        'parameters' => [['type' => 'text', 'text' => $otp]]
                                    ]
                                ]
                            ]
                        ]),
                        CURLOPT_HTTPHEADER => [
                            "Accept: */*",
                            "Authorization: Bearer dJEFvrN8T-RhN7XprIFXUcgBNOCfG-ru9rDjhVLAT0P3jO_b2YGd9SEz23thnAok",
                            "Content-Type: application/json",
                        ],
                    ]);
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);
                    return !$err;
                } catch (\Exception $e) {
                    Log::error('LionSMS Exception', ['error' => $e->getMessage()]);
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

            // Primary Admin numbers to receive lead notifications
            $adminNumbers = ['919943930432', '918807060809', '919751048974'];
            
            // Check dynamic database settings for whatsapp business number
            try {
                $settingPhone = \DB::table('settings')->where('key', 'whatsapp_business_number')->value('value') 
                             ?? \DB::table('settings')->where('key', 'business_phone')->value('value');
                if ($settingPhone) {
                    $cleanSettingPhone = preg_replace('/[^0-9]/', '', $settingPhone);
                    if (strlen($cleanSettingPhone) === 10) {
                        $cleanSettingPhone = '91' . $cleanSettingPhone;
                    }
                    if (strlen($cleanSettingPhone) === 12 && !in_array($cleanSettingPhone, $adminNumbers)) {
                        $adminNumbers[] = $cleanSettingPhone;
                    }
                }
            } catch (\Exception $settingEx) {
                // Ignore DB error if table is unavailable
            }

            $successCount = 0;

            foreach (array_unique($adminNumbers) as $adminPhone) {
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
                    $err = curl_error($curl);
                    curl_close($curl);

                    Log::info('WhatsApp Admin Lead Message Meta Response', [
                        'admin_phone' => $adminPhone,
                        'order_id' => $order_id,
                        'response' => $response,
                        'error' => $err
                    ]);

                    if (!$err && !empty($response)) {
                        $successCount++;
                    }
                } catch (\Exception $e) {
                    Log::error('WhatsApp Admin Lead Message Integration Exception', ['error' => $e->getMessage()]);
                }

                // 2. Also send via legacy v2 endpoint as backup
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
                    curl_close($curl2);

                    Log::info('WhatsApp Admin Lead Message v2 Response', [
                        'admin_phone' => $adminPhone,
                        'response' => $response2
                    ]);
                } catch (\Exception $e2) {
                    Log::error('WhatsApp Admin Lead Message v2 Exception', ['error' => $e2->getMessage()]);
                }
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