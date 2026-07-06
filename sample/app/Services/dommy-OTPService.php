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
                'email' => $phone . '@wa.local', // dummy email
                'password' => null, // no password for OTP users
            ]
        );

        // Log OTP send
        OtpLog::create([
            'phone' => $phone,
            'otp' => $otp,
            'sent_at' => Carbon::now(),
            'expires_at' => $expiresAt,
            'channel' => 'whatsapp',
            'status' => 'sent',
        ]);

        // Generate WhatsApp deep link
        $message = "Your OTP for Cracker Shop login is: {$otp}\nPhone: {$phone}\nValid for {$duration} minutes (expires at {$expiresAt->format('H:i')}).";
        $whatsappLink = $this->generateWhatsAppLink($phone, $message);

        return [
            'user' => $user,
            'whatsapp_link' => $whatsappLink,
            'message' => $message,
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

    private function generateWhatsAppLink(string $phone, string $message): string
    {
        $encodedMessage = urlencode($message);
        return "https://wa.me/91{$phone}?text={$encodedMessage}";
    }
} 