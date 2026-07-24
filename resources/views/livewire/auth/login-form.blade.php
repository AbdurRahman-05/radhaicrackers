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
                    <input wire:model.live="name" id="name" name="name" type="text" required 
                           oninput="this.value = this.value.replace(/[0-9]/g, '')"
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 sm:text-base"
                           placeholder="Enter your full name (letters only)">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
                    <svg class="h-5 w-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    Send OTP via SMS
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

            @if ($devOtp)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-blue-800">Development Mode - OTP Available</h3>
                            <div class="mt-1 text-sm text-blue-700">
                                <p><strong>OTP for {{ $phone }}:</strong> <span class="font-mono text-lg bg-blue-100 px-2 py-1 rounded">{{ $devOtp }}</span></p>
                                <p><strong>Expires at:</strong> {{ \Carbon\Carbon::parse($devOtpExpires)->format('H:i:s') }}</p>
                                <p class="text-xs mt-1">This OTP is stored in session for testing. Configure SMS provider for real delivery.</p>
                            </div>
                        </div>
                    </div>
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
            
            <div class="mt-4 text-center">
                <button type="button" wire:click="sendOTP" 
                        class="text-sm text-gray-600 hover:text-gray-800 underline">
                    Resend OTP
                </button>
            </div>
        @endif
    </form>
    <div class="mt-8 text-center text-xs text-gray-400">
        <span>We never share your phone number. OTP is sent only via SMS.</span>
    </div>
</div> 