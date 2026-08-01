<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.sms.key');
    }

    public function sendOTP($phone, $otp)
    {
        try {
            $phone = preg_replace('/[^0-9]/', '', $phone);
            
            $url = "http://liontech.co.in/api/v2/sms/send";
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($url, [
                'recipient' => $phone,
                'message' => "Your OTP for login is: {$otp}. Valid for 10 minutes. Radhe Crackers",
                'sender_id' => 'RDHCRK',
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
        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2);
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
                        'to' => '+91' . $phone,
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

                // 2. Generate PDF bill in memory and send as document attachment
                try {
                    $order = \App\Models\Order::with(['user', 'payment', 'logs'])->find($order_id);
                    if ($order) {
                        // Generate PDF binary
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.user-order-invoice', compact('order'))->setPaper('a4', 'portrait');
                        $pdfContent = $pdf->output();
                        
                        // Save to a temporary file
                        $tmpFile = storage_path("app/temp_invoice_{$order_id}.pdf");
                        file_put_contents($tmpFile, $pdfContent);

                        // Step A: Upload the PDF to WhatsApp media API
                        $uploadCurl = curl_init();
                        $cFile = new \CURLFile($tmpFile, 'application/pdf', "Radhe_Crackers_Bill_{$order_id}.pdf");
                        curl_setopt_array($uploadCurl, array(
                            CURLOPT_URL => 'https://waapi.automationclub.in/api/integration/whatsapp-message/747598631767762/media',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => [
                                'messaging_product' => 'whatsapp',
                                'file' => $cFile,
                                'type' => 'application/pdf',
                            ],
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: Bearer dJEFvrN8T-RhN7XprIFXUcgBNOCfG-ru9rDjhVLAT0P3jO_b2YGd9SEz23thnAok',
                            ),
                        ));
                        $uploadResponse = curl_exec($uploadCurl);
                        curl_close($uploadCurl);
                        Log::info('WhatsApp PDF media upload response', ['response' => $uploadResponse]);

                        $uploadData = json_decode($uploadResponse, true);
                        $mediaId = $uploadData['id'] ?? null;

                        if ($mediaId) {
                            // Step B: Send document message using uploaded media ID
                            $docCurl = curl_init();
                            curl_setopt_array($docCurl, array(
                                CURLOPT_URL => 'https://waapi.automationclub.in/api/integration/whatsapp-message/747598631767762/messages',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => json_encode([
                                    'messaging_product' => 'whatsapp',
                                    'recipient_type' => 'individual',
                                    'to' => '+91' . $phone,
                                    'type' => 'document',
                                    'document' => [
                                        'id' => $mediaId,
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
                            Log::info('WhatsApp PDF document sent via media ID', ['phone' => $phone, 'response' => $docResponse]);
                        } else {
                            Log::warning('WhatsApp media upload failed, no media ID returned', ['response' => $uploadResponse]);
                        }

                        // Clean up temp file
                        @unlink($tmpFile);
                    }
                } catch (\Exception $pdfEx) {
                    Log::error('WhatsApp PDF generation/upload error', ['error' => $pdfEx->getMessage()]);
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
                        'to' => '+91' . $phone,
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
            $template_name = "thanks_purchasing";
            $name = $data['customer_name'] ?? "Customer"; 
            $order_value = $data['order_value'] ?? "₹0.00"; 
            $order_id = $data['order_id'] ?? "0";
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
                        'to' => '+91' . $phone,
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
                return true;
            } catch (\Exception $e) {
                Log::error('LionSMS Exception', ['error' => $e->getMessage()]);
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
                            'to' => "+91" . $phone,
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
            $order_value = $data['order_value'] ?? "₹0.00"; 
            $order_id = $data['order_id'] ?? "0";
            $bodyParams = [$order_id, $order_value, $phone];

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
                        'to' => '9943930432',
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
                return true;
            } catch (\Exception $e) {
                Log::error('LionSMS Exception', ['error' => $e->getMessage()]);
                return false;
            }
        }
        return false;
    }
}