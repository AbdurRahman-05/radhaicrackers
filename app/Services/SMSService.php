<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class SMSService
{
    protected $apiKey;
    protected $senderId;
    protected $route;
    protected $baseUrl;
    protected $otpTemplateId;

    public function __construct()
    {
        $this->apiKey        = config('services.lionsms.api_key');
        $this->senderId      = config('services.lionsms.sender_id');
        $this->route         = config('services.lionsms.route');
        $this->baseUrl       = config('services.lionsms.base_url');
        $this->otpTemplateId = config('services.lionsms.otp_template_id');
    }

    public function sendOtp($phone, $otp, $context = 'Login')
    {
        // Use the exact message and template as provided
        $message = "Your OTP for Login {$otp} Please do not share this code with anyone for your security. -Radhe Traders";
        $params = [
            'key'        => $this->apiKey,
            'sender'     => $this->senderId,
            'number'     => $phone,
            'route'      => 7, // Use route 7 for OTP as per user API
            'sms'        => $message,
            'templateid' => $this->otpTemplateId,
        ];

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(10)->get($this->baseUrl, $params);

            if ($response->successful()) {
                $data = $response->json();
                // Accept any successful HTTP response as success for now
                return true;
            } else {
                \Illuminate\Support\Facades\Log::error('LionSMS HTTP error', ['response' => $response->body()]);
                return false;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('LionSMS Exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function sendWhatsApp($phone, $otp, $context=null, $data=[]){
        if($context === 'order_confirmation' && !empty($data)){
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
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                "to": "+91'.$phone.'",
                "phoneNoId": "747598631767762",
                "type": "template",
                "name": "'.$template_name.'",
                "language": "en_US",
                "bodyParams": '.json_encode($bodyParams).'
                }',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ca4869c05587ab6e2c2052011dfa8190296a1c1d08a357f7d4a5f6e89e9568b7',
                    'Content-Type: application/json'
                ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                return true;

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('LionSMS Exception', ['error' => $e->getMessage()]);
                return false;
            }
        } else {

            if($context === 'otp' && !empty($otp)){

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
                    'to' => "+91'.$phone.'",
                    'type' => 'template',
                    'template' => [
                        'name' => 'otp',
                        'language' => [
                            'code' => 'en_GB'
                        ],
                        'components' => [
                                [
                                    'type' => 'body',
                                    'parameters' => [
                                                        [
                                                        'type' => 'text',
                                                        'text' => $otp
                                                        ]
                                                    ]
                                ],
                                [
                                    'type' => 'button',
                                    'sub_type' => 'url',
                                    'index' => '0',
                                    'parameters' => [
                                                        [
                                                            'type' => 'text',
                                                            'text' => $otp
                                                        ]
                                                ]
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

                if ($err) {
                    return false;
                } else {
                    return true;
                }

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('LionSMS Exception', ['error' => $e->getMessage()]);
                return false;
            }
        }

    }  
    
    

    }

    public function sendWhatsAppAdmin($phone, $otp, $context=null, $data=[]){
        if($context === 'order_confirmation' && !empty($data)){
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
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                "to": "9943930432",
                "phoneNoId": "747598631767762",
                "type": "template",
                "name": "'.$template_name.'",
                "language": "en_US",
                "bodyParams": '.json_encode($bodyParams).'
                }',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ca4869c05587ab6e2c2052011dfa8190296a1c1d08a357f7d4a5f6e89e9568b7',
                    'Content-Type: application/json'
                ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                return true;

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('LionSMS Exception', ['error' => $e->getMessage()]);
                return false;
            }
        }
    }
} 