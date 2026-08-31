<div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-900 flex items-center gap-2">
                <span class="p-2 bg-purple-100 text-purple-800 rounded-xl">
                    <i class="fas fa-sliders-h"></i>
                </span>
                Website & Video Settings
            </h2>
            <p class="text-gray-500 text-sm mt-1">Configure opening video popup, business info, and system preferences</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-2">
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition">
                <i class="fas fa-external-link-alt"></i> View Website
            </a>
        </div>
    </div>

    <!-- Flash message -->
    @if(session()->has('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm flex items-center justify-between animate-fadeIn">
            <div class="flex items-center gap-3 text-green-800 font-medium">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-900 text-lg font-bold">&times;</button>
        </div>
    @endif

    <!-- 1. POPUP VIDEO SETTINGS CARD (Primary Feature) -->
    <div class="bg-white border-2 border-yellow-400/50 rounded-2xl shadow-lg overflow-hidden transition-all duration-300">
        <!-- Banner Header -->
        <div class="bg-gradient-to-r from-[#1E093B] via-[#3B156C] to-[#B67121] p-6 text-white flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-yellow-400/20 border border-yellow-300/40 flex items-center justify-center text-yellow-300 text-2xl shadow-inner flex-shrink-0">
                    <i class="fab fa-youtube"></i>
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-black tracking-wide text-white">Opening Video Popup</h3>
                        @if($popupVideoEnabled)
                            <span class="bg-green-500 text-white text-xs font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider animate-pulse flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-white"></span> Active
                            </span>
                        @else
                            <span class="bg-gray-500/60 text-gray-200 text-xs font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                Disabled
                            </span>
                        @endif
                    </div>
                    <p class="text-yellow-200/90 text-sm mt-0.5">
                        Shows an engaging, responsive YouTube video popup when visitors open your website.
                    </p>
                </div>
            </div>

            <!-- Master Toggle Switch -->
            <label class="relative inline-flex items-center cursor-pointer select-none bg-white/10 p-2 px-4 rounded-xl border border-white/20 hover:bg-white/20 transition">
                <input type="checkbox" wire:model.live="popupVideoEnabled" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[11px] after:left-[19px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-yellow-400"></div>
                <span class="ml-3 text-sm font-bold text-white">
                    {{ $popupVideoEnabled ? 'Popup Enabled' : 'Popup Disabled' }}
                </span>
            </label>
        </div>

        <!-- Form Body -->
        <div class="p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left Column: Settings Inputs -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Video URL Field -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-1.5 flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <i class="fab fa-youtube text-red-600 text-lg"></i>
                                YouTube Video URL or Link <span class="text-red-500">*</span>
                            </span>
                            <span class="text-xs text-gray-400 font-normal">Supports standard, youtu.be & shorts</span>
                        </label>
                        <div class="relative">
                            <input wire:model.live.debounce.500ms="popupVideoUrl" 
                                   type="text" 
                                   placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/..." 
                                   class="w-full pl-10 pr-4 py-3 border-2 {{ $errors->has('popupVideoUrl') ? 'border-red-400' : 'border-gray-200 focus:border-purple-600' }} rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-100 font-medium transition text-gray-800 text-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-link"></i>
                            </div>
                        </div>
                        @error('popupVideoUrl') <span class="text-red-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            Paste any link like <code class="bg-gray-100 px-1 py-0.5 rounded text-gray-700">https://www.youtube.com/watch?v=xxxx</code> or <code class="bg-gray-100 px-1 py-0.5 rounded text-gray-700">https://youtu.be/xxxx</code>
                        </p>
                    </div>

                    <!-- Popup Title -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-1.5">
                            Popup Header Title / Caption
                        </label>
                        <input wire:model="popupVideoTitle" 
                               type="text" 
                               placeholder="✨ Radhe Crackers - Festival Specials & Greetings" 
                               class="w-full px-4 py-2.5 border-2 border-gray-200 focus:border-purple-600 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-100 font-medium text-gray-800 text-sm transition">
                        @error('popupVideoTitle') <span class="text-red-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Display Frequency & Pages (2 cols) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Frequency -->
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-1.5">
                                <i class="fas fa-clock text-gray-400 mr-1"></i> Display Frequency
                            </label>
                            <select wire:model="popupVideoFrequency" class="w-full px-3 py-2.5 border-2 border-gray-200 focus:border-purple-600 rounded-xl font-medium text-sm text-gray-800 bg-white">
                                <option value="always">Every Site Open / Refresh (Always)</option>
                                <option value="once_per_session">Once Per Browser Session (Recommended)</option>
                                <option value="once_per_day">Once Per Day (24 Hours)</option>
                            </select>
                        </div>

                        <!-- Target Pages -->
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-1.5">
                                <i class="fas fa-layer-group text-gray-400 mr-1"></i> Show On
                            </label>
                            <select wire:model="popupVideoShowOn" class="w-full px-3 py-2.5 border-2 border-gray-200 focus:border-purple-600 rounded-xl font-medium text-sm text-gray-800 bg-white">
                                <option value="home">Homepage Only (Recommended)</option>
                                <option value="all">All Pages (First Arrival)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Autoplay & Muted Switches -->
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 space-y-3">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Playback Options</span>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-sm font-bold text-gray-800 block">Autoplay Video</span>
                                <span class="text-xs text-gray-500">Automatically play video when popup appears</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="popupVideoAutoplay" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                            <div>
                                <span class="text-sm font-bold text-gray-800 block">Start Muted (Recommended for Browsers)</span>
                                <span class="text-xs text-gray-500">Modern browsers (Chrome, Safari) require muted audio for autoplay</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="popupVideoMuted" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Call To Action Button (Inside Popup) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-1.5">
                                CTA Button Text
                            </label>
                            <input wire:model="popupVideoCtaText" 
                                   type="text" 
                                   placeholder="🔥 Explore Products & Offers" 
                                   class="w-full px-4 py-2.5 border-2 border-gray-200 focus:border-purple-600 rounded-xl font-medium text-sm text-gray-800">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-1.5">
                                CTA Button Link
                            </label>
                            <input wire:model="popupVideoCtaUrl" 
                                   type="text" 
                                   placeholder="/sale-products or /express-shop" 
                                   class="w-full px-4 py-2.5 border-2 border-gray-200 focus:border-purple-600 rounded-xl font-medium text-sm text-gray-800">
                        </div>
                    </div>
                </div>

                <!-- Right Column: Live Interactive Preview -->
                <div class="lg:col-span-5 flex flex-col">
                    <label class="block text-sm font-bold text-gray-800 mb-2 flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-eye text-purple-600"></i> Live Popup Preview
                        </span>
                        <span class="text-xs font-semibold px-2 py-0.5 bg-purple-100 text-purple-800 rounded-full">Interactive</span>
                    </label>

                    <div class="flex-1 bg-gradient-to-br from-gray-900 to-[#1E093B] p-4 rounded-2xl border-2 border-yellow-400/40 shadow-xl flex flex-col justify-center items-center text-center relative overflow-hidden min-h-[300px]">
                        <!-- Decorative glow -->
                        <div class="absolute -top-12 -right-12 w-36 h-36 bg-yellow-400/20 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="absolute -bottom-12 -left-12 w-36 h-36 bg-purple-600/30 rounded-full blur-2xl pointer-events-none"></div>

                        @if(!empty($this->embedUrl))
                            <div class="w-full space-y-3 z-10">
                                <!-- Preview Header Bar -->
                                <div class="flex items-center justify-between text-white border-b border-white/10 pb-2 px-1">
                                    <span class="text-xs font-bold truncate max-w-[220px] text-yellow-300">
                                        {{ $popupVideoTitle ?: 'Special Video' }}
                                    </span>
                                    <span class="w-6 h-6 rounded-full bg-white/20 text-white flex items-center justify-center text-xs cursor-pointer hover:bg-white/30">&times;</span>
                                </div>

                                <!-- Responsive iframe preview -->
                                <div class="relative w-full aspect-video rounded-xl overflow-hidden shadow-2xl border border-yellow-400/30 bg-black">
                                    <iframe src="{{ $this->embedUrl }}" 
                                            class="w-full h-full" 
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen>
                                    </iframe>
                                </div>

                                <!-- Preview CTA button -->
                                <div class="pt-2 flex items-center justify-center gap-2">
                                    <a href="javascript:void(0)" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-yellow-400 to-amber-500 text-purple-950 font-black text-xs rounded-full shadow-md hover:scale-105 transition">
                                        {{ $popupVideoCtaText ?: 'Explore Products' }} &rarr;
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="z-10 py-10 px-4 text-center">
                                <div class="w-16 h-16 rounded-full bg-white/10 border border-white/20 flex items-center justify-center mx-auto mb-3 text-yellow-400 text-3xl">
                                    <i class="fab fa-youtube"></i>
                                </div>
                                <h4 class="text-white font-bold text-base mb-1">No Video URL Added</h4>
                                <p class="text-gray-400 text-xs max-w-xs mx-auto">
                                    Paste your YouTube link on the left to see a live preview of how the popup will look to customers!
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Save Video Popup Settings Button -->
            <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-gray-500 flex items-center gap-1.5">
                    <i class="fas fa-shield-alt text-green-600"></i> Settings will take effect immediately for all site visitors.
                </div>
                <button wire:click="savePopupVideoSettings" 
                        wire:loading.attr="disabled"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#1E093B] via-[#3B156C] to-[#B67121] hover:from-[#2D0B5A] hover:to-[#C97F29] text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-[1.02] cursor-pointer">
                    <span wire:loading.remove wire:target="savePopupVideoSettings" class="flex items-center gap-2">
                        <i class="fas fa-save"></i> Save Video Popup Settings
                    </span>
                    <span wire:loading wire:target="savePopupVideoSettings" class="flex items-center gap-2">
                        <i class="fas fa-spinner fa-spin"></i> Saving Settings...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- 2. BUSINESS & STORE SETTINGS -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-lg">
                <i class="fas fa-store"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Business & Store Information</h3>
                <p class="text-gray-500 text-xs">Primary contact and payment details shown across invoices & headers</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Business Name</label>
                <input wire:model="businessName" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                @error('businessName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Business Email</label>
                <input wire:model="businessEmail" type="email" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                @error('businessEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Business Phone / Contact</label>
                <input wire:model="businessPhone" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                @error('businessPhone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">UPI ID (For Payments)</label>
                <input wire:model="upiId" type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                @error('upiId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-bold text-gray-700 mb-1">Business Address</label>
            <textarea wire:model="businessAddress" rows="3" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"></textarea>
            @error('businessAddress') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mt-6 flex justify-end">
            <button wire:click="saveBusinessSettings" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl text-sm transition">
                <i class="fas fa-save mr-1"></i> Save Business Settings
            </button>
        </div>
    </div>

    <!-- 3. STORE STATISTICS OVERVIEW -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-blue-50 p-5 rounded-2xl border border-blue-100">
            <div class="text-3xl font-black text-blue-600">{{ $totalUsers }}</div>
            <div class="text-xs font-bold text-blue-700 uppercase tracking-wider mt-1">Total Users</div>
        </div>
        <div class="bg-green-50 p-5 rounded-2xl border border-green-100">
            <div class="text-3xl font-black text-green-600">{{ $totalOrders }}</div>
            <div class="text-xs font-bold text-green-700 uppercase tracking-wider mt-1">Total Orders</div>
        </div>
        <div class="bg-yellow-50 p-5 rounded-2xl border border-yellow-100">
            <div class="text-3xl font-black text-yellow-600">{{ $totalStocks }}</div>
            <div class="text-xs font-bold text-yellow-700 uppercase tracking-wider mt-1">Total Products</div>
        </div>
        <div class="bg-purple-50 p-5 rounded-2xl border border-purple-100">
            <div class="text-3xl font-black text-purple-600">{{ $totalPayments }}</div>
            <div class="text-xs font-bold text-purple-700 uppercase tracking-wider mt-1">Total Payments</div>
        </div>
    </div>
</div>