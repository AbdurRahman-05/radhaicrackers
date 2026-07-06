<?php

namespace App\Http\Controllers;

use App\Services\OTPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class OTPController extends Controller
{
    protected $otpService;

    public function __construct(OTPService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function sendOTP(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10}$/',
        ]);

        $result = $this->otpService->sendOTP($request->phone, $request->name);

        return response()->json([
            'success' => true,
            'whatsapp_link' => $result['whatsapp_link'],
            'message' => 'OTP sent successfully! Check your WhatsApp.',
        ]);
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string|size:6',
        ]);

        $user = $this->otpService->verifyOTP($request->phone, $request->otp);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP or OTP expired.',
            ], 422);
        }

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Login successful!',
            'redirect' => route('user.dashboard'),
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }


} 