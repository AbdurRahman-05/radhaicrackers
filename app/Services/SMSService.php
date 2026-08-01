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

    public function sendOTP($phone, $otp)
    {
        $rawPhone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($rawPhone) === 12 && str_starts_with($rawPhone, '91')) {
            $rawPhone = substr($rawPhone, 2);
        }

        $baseUrl = config('services.lionsms.base_url', 'https://msg.lionsms.com/api/smsapi');
        $apiKey = config('services.lionsms.api_key', 'dcd3c5c00112b83116657d7f656660a1');
        $senderId = config('services.lionsms.sender_id', 'RADHTR');
        $route = config('services.lionsms.route', '9');
        $templateId = config('services.lionsms.otp_template_id', '1107172187374253331');

        $message = "Your OTP for login is: {$otp}. Valid for 10 minutes. Radhe Crackers";

        try {
            $response = Http::get($baseUrl, [
                'api_key' => $apiKey,
                'type' => 'text',
                'contacts' => $rawPhone,
                'senderid' => $senderId,
                'msg' => $message,
                'template_id' => $templateId,
                'route' => $route,
            ]);

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
                        
                        // Use production domain URL (Meta WhatsApp API cannot reach localhost)
                        $productionDomain = 'https://mediumspringgreen-dragonfly-181890.hostingersite.com';
                        $pdfUrl = $productionDomain . '/storage/' . $pdfFilename;

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