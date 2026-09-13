<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendOtpRequest;
use App\Models\Otp;
use App\Models\User;
use App\Services\SMSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OTPLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.otp-login');
    }

    public function sendOtp(SendOtpRequest $request, SMSService $smsService)
    {
        $phone = $request->input('phone');
        $name = $request->input('name');
        $otp = random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(5);
        $channel = $request->input('channel', 'sms'); // Default to SMS if not provided

        session(['register_name_' . $phone => $name]);

        Otp::create([
            'phone' => $phone,
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'is_verified' => false,
        ]);

        $sent = false;
        $sentMessage = 'OTP sent successfully.';

        if ($channel === 'sms') {
            $sent = $smsService->sendOtp($phone, $otp);
            $sentMessage = 'OTP sent to your mobile number via SMS.';
        } else if ($channel === 'whatsapp') {
            $sent = $smsService->sendWhatsApp($phone, $otp, 'otp', []);
            if ($sent) {
                $sentMessage = 'OTP sent to your WhatsApp number.';
            } else {
                // Auto-fallback to SMS if WhatsApp dispatch fails
                $fallbackSent = $smsService->sendOtp($phone, $otp);
                if ($fallbackSent) {
                    $sent = true;
                    $sentMessage = 'WhatsApp delivery was temporarily unavailable. OTP has been sent via SMS to your mobile.';
                }
            }
        }

        if ($sent) {
            return back()->with('success', $sentMessage)->withInput();
        } else {
            return back()->withErrors(['phone' => 'Failed to send OTP via ' . strtoupper($channel) . '. Please try another channel or try again in a few moments.'])->withInput();
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^[6-9]\\d{9}$/'],
            'otp' => ['required', 'digits:6'],
        ]);

        $otpRecord = Otp::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->where('is_verified', false)
            ->where('expires_at', '>', now())
            ->first();

        if ($otpRecord) {
            $otpRecord->update(['is_verified' => true]);
            
            $name = session('register_name_' . $request->phone, 'User-' . $request->phone);
            
            $user = User::firstOrCreate(
                ['phone' => $request->phone],
                [
                    'name' => $name,
                    'password' => Hash::make(Str::random(12)),
                    'is_active' => true,
                ]
            );

            if ($name !== 'User-' . $request->phone && ($user->name === 'User-' . $request->phone || empty($user->name))) {
                $user->update(['name' => $name]);
            }
            
            session()->forget('register_name_' . $request->phone);

            Auth::login($user);
            return redirect('/');
        } else {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }
    }
} 