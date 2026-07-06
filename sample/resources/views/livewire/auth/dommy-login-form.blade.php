<div>
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if ($message)
        <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
            {{ $message }}
        </div>
    @endif

    <form wire:submit.prevent="{{ $showOtpField ? 'verifyOTP' : 'sendOTP' }}" class="space-y-6">
        @if (!$showOtpField)
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-base font-semibold text-gray-700 mb-1">Full Name</label>
                    <input wire:model="name" id="name" name="name" type="text" required 
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 sm:text-base"
                           placeholder="Enter your full name">
                    @error('name') <span class="text-gray-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="phone" class="block text-base font-semibold text-gray-700 mb-1">Phone Number</label>
                    <input wire:model="phone" id="phone" name="phone" type="tel" required 
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 sm:text-base"
                           placeholder="Enter 10-digit phone number">
                    @error('phone') <span class="text-gray-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent text-base font-bold rounded-lg text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition"style="background-color: #1E093B;">
                    <svg class="h-5 w-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 01-8 0m8 0V7a4 4 0 10-8 0v5m8 0a4 4 0 01-8 0" /></svg>
                    Send OTP via WhatsApp
                </button>
            </div>
        @else
            <div>
                <label for="otp" class="block text-base font-semibold text-gray-700 mb-1">Enter OTP</label>
                <input wire:model="otp" id="otp" name="otp" type="text" required maxlength="6"
                       class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 sm:text-2xl text-center tracking-widest"
                       placeholder="000000">
                @error('otp') <span class="text-gray-500 text-sm">{{ $message }}</span> @enderror
            </div>
            @if ($whatsappLink)
                <div class="text-center mt-2">
                    <a href="{{ $whatsappLink }}" target="_blank" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        Open WhatsApp
                    </a>
                </div>
            @endif
            <div class="flex space-x-4 mt-4">
                <button type="submit" 
                        class="flex-1 flex justify-center py-3 px-4 border border-transparent text-base font-bold rounded-lg text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Verify OTP
                </button>
                <button type="button" wire:click="$set('showOtpField', false)"
                        class="flex-1 flex justify-center py-3 px-4 border border-gray-300 text-base font-bold rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Back
                </button>
            </div>
        @endif
    </form>
    <div class="mt-8 text-center text-xs text-gray-400">
        <span>We never share your phone number. OTP is sent only via WhatsApp.</span>
    </div>
</div> 