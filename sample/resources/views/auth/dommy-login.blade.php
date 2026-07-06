@extends('layouts.app')

@section('title', 'Login - Cracker Shop')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="flex flex-col items-center">
            <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full mb-2" style="background-color: #1E093B;">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11c1.104 0 2-.896 2-2V7a2 2 0 10-4 0v2c0 1.104.896 2 2 2zm6 2v5a2 2 0 01-2 2H8a2 2 0 01-2-2v-5a6 6 0 1112 0z"/></svg>
            </div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900">
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Cracker Shop Platform
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <livewire:auth.login-form />
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-500">Enter your <span class="font-semibold text-gray-900">Name</span> and <span class="font-semibold text-gray-600">Phone Number</span> to get an OTP via WhatsApp.<br>Use the OTP to log in securely.</p>
        </div>
    </div>
</div>
@endsection 