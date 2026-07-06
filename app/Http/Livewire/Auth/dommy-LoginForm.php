<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Services\OTPService;

class LoginForm extends Component
{
    public $name = '';
    public $phone = '';
    public $otp = '';
    public $showOtpField = false;
    public $whatsappLink = '';
    public $message = '';

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

        $this->whatsappLink = $result['whatsapp_link'];
        $this->showOtpField = true;
        $this->message = 'OTP sent successfully! Check your WhatsApp.';
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
            $this->message = 'Invalid OTP or OTP expired.';
        }
    }

    public function render()
    {
        return view('livewire.auth.login-form');
    }
} 