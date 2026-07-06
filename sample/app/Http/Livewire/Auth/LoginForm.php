<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Services\OTPService;
use Carbon\Carbon;
use App\Models\User;

class LoginForm extends Component
{
    public $name = '';
    public $phone = '';
    public $otp = '';
    public $showOtpField = false;
    public $message = '';
    public $timeRemaining = ''; // For countdown display
    public $devOtp = ''; // For development mode
    public $devOtpExpires = ''; // For development mode

    protected $rules = [
        'name' => 'required|string|max:255',
        'phone' => 'required|string|regex:/^[0-9]{10}$/',
        'otp' => 'required|string|size:6',
    ];

    public function sendOTP()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10}$/',
        ]);

        $otpService = app(OTPService::class);
        $result = $otpService->sendOTP($this->phone, $this->name);

        $this->showOtpField = true;
        $this->message = $result['message'];
        
        // Check if we're in development mode and show OTP
        $this->checkDevelopmentOTP();
    }

    public function verifyOTP()
    {
        $this->validate([
            'otp' => 'required|string|size:6',
        ]);

        $otpService = app(OTPService::class);
        $user = $otpService->verifyOTP($this->phone, $this->otp);

        if ($user) {
            auth()->login($user);
            $this->message = 'Login successful!';
            return redirect()->route('user.dashboard');
        } else {
            // Check if OTP exists but is expired
            $user = User::where('phone', $this->phone)->first();
            if ($user && $user->otp && $user->otp_expires_at && $user->otp_expires_at < Carbon::now()) {
                $this->message = 'OTP has expired. Please request a new OTP.';
            } else {
                $this->message = 'Invalid OTP. Please check and try again.';
            }
        }
    }

    public function checkDevelopmentOTP()
    {
        // Check if SMS provider is not configured (development mode)
        $smsProvider = config('services.sms.provider', 'none');
        $apiKey = config('services.sms.api_key');
        
        if (!$apiKey || $smsProvider === 'none') {
            $devOtp = session('dev_otp_' . $this->phone);
            $devOtpExpires = session('dev_otp_expires_' . $this->phone);
            
            if ($devOtp && $devOtpExpires) {
                $this->devOtp = $devOtp;
                $this->devOtpExpires = $devOtpExpires;
            }
        }
    }

    public function render()
    {
        return view('livewire.auth.login-form');
    }
} 