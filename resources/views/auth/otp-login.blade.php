@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Login with OTP</h2>
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-2">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('login.sendOtp') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Full Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name') }}" required oninput="this.value = this.value.replace(/[0-9]/g, '')" placeholder="Enter your full name (letters only)">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Phone Number</label>
            <input type="text" name="phone" class="w-full border rounded px-3 py-2" value="{{ old('phone') }}" required placeholder="Enter 10-digit phone number">
            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="inline-flex items-center cursor-pointer">
                <input type="radio" name="channel" value="whatsapp" {{ old('channel') === 'whatsapp' ? 'checked' : '' }} class="text-green-600 focus:ring-green-500">
                <span class="ml-2 text-sm font-medium text-gray-700 flex items-center">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500 mr-1.5"></span> WhatsApp
                </span>
            </label>
            <label class="inline-flex items-center cursor-pointer ml-6">
                <input type="radio" name="channel" value="sms" {{ old('channel', 'sms') === 'sms' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm font-medium text-gray-700 flex items-center">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-500 mr-1.5"></span> SMS
                </span>
            </label>
        </div>
        <p class="text-xs text-orange-600 mb-4 mt-[-10px]">* Note: WhatsApp OTP is temporarily unavailable. Please use SMS.</p>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded shadow transition-colors">Send OTP</button>
    </form>

    @if(session('success') || old('phone'))
    <form method="POST" action="{{ route('login.verifyOtp') }}" class="mt-6 pt-6 border-t border-gray-100">
        @csrf
        <input type="hidden" name="phone" value="{{ old('phone') }}">
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Enter 6-Digit OTP</label>
            <input type="text" name="otp" class="w-full border rounded px-3 py-2 text-center text-lg tracking-widest font-mono" maxlength="6" placeholder="------" required autofocus>
            @error('otp') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2.5 rounded shadow transition-colors">Verify & Login</button>
    </form>
    @endif
</div>
@endsection 