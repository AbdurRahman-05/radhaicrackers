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
            <label><input type="radio" name="channel" value="sms" checked> SMS </label>
            <label style="margin-left:1rem"><input type="radio" name="channel" value="whatsapp"> WhatsApp </label>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Send OTP</button>
    </form>

    @if(session('success') || old('phone'))
    <form method="POST" action="{{ route('login.verifyOtp') }}" class="mt-6">
        @csrf
        <input type="hidden" name="phone" value="{{ old('phone') }}">
        <div class="mb-4">
            <label>Enter OTP</label>
            <input type="text" name="otp" class="w-full border rounded px-3 py-2">
            @error('otp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Verify OTP</button>
    </form>
    @endif
</div>
@endsection 