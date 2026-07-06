<?php

namespace App\Services;

use App\Models\User;
use App\Models\OtpLog;
use Carbon\Carbon;

class OTPService
{
    public function generateOTP(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function sendOTP(string $phone, string $name): array
    {
        $otp = $this->generateOTP();
        $expiresAt = Carbon::now()->addMinutes(10);
        $duration = $expiresAt->diffInMinutes(Carbon::now());

        // Find or create user
        $user = User::updateOrCreate(
            ['phone' => $phone],
            [
                'name' => $name,
                'otp' => $otp,
                'otp_expires_at' => $expiresAt,
                'email' => $phone . '@sms.local', // dummy email
                'password' => null, // no password for OTP users
            ]
        );

        // Log OTP send
        OtpLog::create([
            'phone' => $phone,
            'otp' => $otp,
            'sent_at' => Carbon::now(),
            'expires_at' => $expiresAt,
            'channel' => 'sms',
            'status' => 'sent',
        ]);

        // Send SMS with OTP
        $this->sendSMS($phone, $otp, $duration, $expiresAt);

        return [
            'user' => $user,
            'message' => "OTP sent successfully to {$phone} via SMS!",
        ];
    }

    public function verifyOTP(string $phone, string $otp): ?User
    {
        $user = User::where('phone', $phone)
            ->where('otp', $otp)
            ->where('otp_expires_at', '>', Carbon::now())
            ->first();

        if ($user) {
            // Clear OTP after successful verification
            $user->update([
                'otp' => null,
                'otp_expires_at' => null,
            ]);
        }

        return $user;
    }

    private function sendSMS(string $phone, string $otp, int $duration, Carbon $expiresAt): void
    {
        $message = "Your OTP for Cracker Shop login is: {$otp}. Valid for {$duration} minutes (expires at {$expiresAt->format('H:i')}).";
        
        // Try to send via SMS API first
        $smsSent = $this->sendViaSMSAPI($phone, $message);
        
        if (!$smsSent) {
            // Fallback: Log the SMS for development/testing
            \Log::info("SMS sent to {$phone}: {$message}");
            
            // In development mode, also show OTP in browser console
            if (app()->environment('local')) {
                \Log::info("DEVELOPMENT MODE - OTP for {$phone}: {$otp}");
            }
            
            // Store OTP in session for development testing
            session(['dev_otp_' . $phone => $otp]);
            session(['dev_otp_expires_' . $phone => $expiresAt]);
        }
    }

    // SMS API integration method
    private function sendViaSMSAPI(string $phone, string $message): bool
    {
        // Check if SMS API is configured
        $smsProvider = config('services.sms.provider', 'none');
        $apiKey = config('services.sms.api_key');
        
        if (!$apiKey || $smsProvider === 'none') {
            return false;
        }
        
        try {
            switch ($smsProvider) {
                case 'msg91':
                    return $this->sendViaMSG91($phone, $message);
                case 'twilio':
                    return $this->sendViaTwilio($phone, $message);
                case 'fast2sms':
                    return $this->sendViaFast2SMS($phone, $message);
                case 'textlocal':
                    return $this->sendViaTextLocal($phone, $message);
                default:
                    return false;
            }
        } catch (\Exception $e) {
            \Log::error("SMS sending failed: " . $e->getMessage());
            return false;
        }
    }
    
    private function sendViaMSG91(string $phone, string $message): bool
    {
        $apiKey = config('services.sms.api_key');
        $senderId = config('services.sms.sender_id', 'CRACKR');
        
        $url = "https://api.msg91.com/api/v5/flow/";
        $data = [
            'flow_id' => config('services.sms.flow_id'),
            'sender' => $senderId,
            'mobiles' => '91' . $phone,
            'VAR1' => substr($message, 0, 6), // Extract OTP from message
        ];
        
        $headers = [
            'Content-Type: application/json',
            'Authkey: ' . $apiKey
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        \Log::info("MSG91 SMS Response: " . $response);
        
        return $httpCode === 200;
    }
    
    private function sendViaTwilio(string $phone, string $message): bool
    {
        $accountSid = config('services.sms.account_sid');
        $authToken = config('services.sms.auth_token');
        $fromNumber = config('services.sms.from_number');
        
        $url = "https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json";
        $data = [
            'To' => '+91' . $phone,
            'From' => $fromNumber,
            'Body' => $message
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_USERPWD, $accountSid . ':' . $authToken);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        \Log::info("Twilio SMS Response: " . $response);
        
        return $httpCode === 201;
    }
    
    private function sendViaFast2SMS(string $phone, string $message): bool
    {
        $apiKey = config('services.sms.api_key');
        $senderId = config('services.sms.sender_id', 'CRACKR');
        
        $url = "https://www.fast2sms.com/dev/bulkV2";
        $data = [
            'authorization' => $apiKey,
            'message' => $message,
            'language' => 'english',
            'route' => 'v3',
            'numbers' => $phone,
            'flash' => 0
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        \Log::info("Fast2SMS Response: " . $response);
        
        return $httpCode === 200;
    }
    
    private function sendViaTextLocal(string $phone, string $message): bool
    {
        $apiKey = config('services.sms.api_key');
        $senderId = config('services.sms.sender_id', 'CRACKR');
        
        $url = "https://api.textlocal.in/send/";
        $data = [
            'apikey' => $apiKey,
            'numbers' => '91' . $phone,
            'message' => $message,
            'sender' => $senderId,
            'test' => config('services.sms.test_mode', false) ? '1' : '0'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        \Log::info("TextLocal SMS Response: " . $response);
        
        return $httpCode === 200;
    }
} 