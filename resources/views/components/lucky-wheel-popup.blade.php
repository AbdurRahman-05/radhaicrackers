<!-- Lucky Spinning Wheel Pop-up Modal & Floating Widget -->
<div id="luckyWheelPopupBackdrop" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4 opacity-0 pointer-events-none transition-all duration-300">
    <div id="luckyWheelPopupDialog" class="relative w-full max-w-2xl bg-gradient-to-b from-[#1E093B] via-[#2A0845] to-[#120024] rounded-2xl sm:rounded-3xl border-2 border-amber-400/60 shadow-[0_0_50px_rgba(245,158,11,0.35)] overflow-hidden transform scale-90 transition-all duration-300 max-h-[92vh] flex flex-col text-white font-sans">
        
        <!-- Festive Top Header Bar with Sparkles -->
        <div class="relative px-5 py-3.5 sm:py-4 bg-gradient-to-r from-amber-500/20 via-orange-500/30 to-purple-600/30 border-b border-amber-400/30 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-2.5">
                <span class="text-2xl sm:text-3xl animate-bounce">🎡</span>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="bg-amber-400 text-purple-950 text-[10px] sm:text-xs font-black px-2 py-0.5 rounded-full uppercase tracking-wider shadow">
                            Exclusive Diwali Dhamaka
                        </span>
                        <span class="hidden sm:inline-block bg-emerald-500/30 text-emerald-300 border border-emerald-400/40 text-[10px] font-bold px-2 py-0.5 rounded-full">
                            Orders &gt; ₹5,000 Only
                        </span>
                    </div>
                    <h3 class="text-base sm:text-xl font-black tracking-tight text-white mt-0.5 flex items-center gap-1.5">
                        Spin The Lucky Wheel &amp; Win!
                    </h3>
                </div>
            </div>
            
            <button onclick="closeLuckyWheelPopup()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors border border-white/20 cursor-pointer" title="Close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Scrollable Modal Content -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-5">
            
            <!-- Headline Banner -->
            <div class="text-center space-y-1">
                <div class="inline-flex items-center gap-1.5 text-xs font-extrabold text-amber-300 uppercase tracking-widest bg-amber-400/10 border border-amber-400/20 px-3 py-1 rounded-full">
                    <span>✨</span> Guaranteed Free Gifts &amp; Extra Discounts <span>✨</span>
                </div>
                <p class="text-xs sm:text-sm text-gray-200 max-w-lg mx-auto leading-relaxed">
                    Make your estimate order <strong class="text-amber-300">₹5,000 or above</strong> and unlock a guaranteed spin at Smart Checkout to win authentic Sivakasi cracker gifts or instant 5% off!
                </p>
            </div>

            <!-- Wheel Showcase + Live Preview Area -->
            <div class="bg-black/30 border border-amber-400/20 rounded-2xl p-4 flex flex-col md:flex-row items-center justify-center gap-6">
                
                <!-- The Wheel Visual -->
                <div class="relative flex flex-col items-center flex-shrink-0">
                    <!-- Top Needle -->
                    <div class="relative z-20 -mb-3.5 flex flex-col items-center drop-shadow-[0_4px_8px_rgba(0,0,0,0.8)]">
                        <div class="w-0 h-0 border-l-[12px] border-l-transparent border-r-[12px] border-r-transparent border-t-[22px] border-t-amber-400"></div>
                        <div class="w-2.5 h-2.5 bg-red-600 rounded-full -mt-4 border-2 border-white shadow"></div>
                    </div>

                    <!-- Canvas -->
                    <div class="relative rounded-full p-2 bg-gradient-to-tr from-amber-600 via-yellow-400 to-amber-600 shadow-[0_0_25px_rgba(245,158,11,0.5)] border-4 border-amber-300">
                        <canvas id="popupWheelCanvas" width="260" height="260" class="block rounded-full"></canvas>
                        
                        <!-- Center Hub / Test Spin Button -->
                        <button type="button" onclick="testSpinPopupWheel()" id="popupSpinBtn" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-14 h-14 rounded-full bg-gradient-to-tr from-amber-500 to-yellow-300 text-purple-950 font-black text-[11px] uppercase tracking-tighter flex flex-col items-center justify-center border-2 border-white shadow-xl hover:scale-105 active:scale-95 transition-transform cursor-pointer z-10">
                            <span>TEST</span>
                            <span class="text-[9px] -mt-0.5">SPIN</span>
                        </button>
                    </div>

                    <span class="text-[10px] text-amber-200/70 mt-2 font-medium flex items-center gap-1">
                        <span>👆 Click center button to test spin!</span>
                    </span>
                </div>

                <!-- 5 Prizes List Cards -->
                <div class="flex-1 w-full space-y-2">
                    <h4 class="text-xs font-black uppercase tracking-wider text-amber-300 flex items-center gap-1.5 pb-1 border-b border-white/10">
                        <span>🎁</span> 5 Exciting Prizes You Can Win:
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        <!-- Prize 1: 25 Raider -->
                        <div class="bg-white/5 hover:bg-white/10 p-2 rounded-xl border border-rose-400/30 flex items-center gap-2.5 transition-colors">
                            <img src="/storage/stocks/716pk6nQfo6s9RIh72gZ114WSakDaBRACNY87Pw2.jpg" alt="25 Raider" class="w-10 h-10 object-cover rounded-lg border border-rose-400/50 flex-shrink-0 bg-white/10" onerror="this.src='/images/placeholder.jpg'">
                            <div class="min-w-0">
                                <div class="font-extrabold text-rose-300 truncate">25 Raider</div>
                                <div class="text-[10px] text-gray-300">Worth <span class="text-amber-300 font-bold">₹220</span> (Free Gift)</div>
                            </div>
                        </div>

                        <!-- Prize 2: 30 Shot Regular -->
                        <div class="bg-white/5 hover:bg-white/10 p-2 rounded-xl border border-purple-400/30 flex items-center gap-2.5 transition-colors">
                            <img src="/storage/stocks/ZMW1truK7lG7qqJ6xcMjArWB6bo8Fm1douNjHrud.jpg" alt="30 Shot Regular" class="w-10 h-10 object-cover rounded-lg border border-purple-400/50 flex-shrink-0 bg-white/10" onerror="this.src='/images/placeholder.jpg'">
                            <div class="min-w-0">
                                <div class="font-extrabold text-purple-300 truncate">30 Shot Regular</div>
                                <div class="text-[10px] text-gray-300">Worth <span class="text-amber-300 font-bold">₹390</span> (Free Gift)</div>
                            </div>
                        </div>

                        <!-- Prize 3: 6 Inch Tin Shower -->
                        <div class="bg-white/5 hover:bg-white/10 p-2 rounded-xl border border-emerald-400/30 flex items-center gap-2.5 transition-colors">
                            <img src="/storage/stocks/yxg0ReFPwjqJXFGS4wpScuToCMhOKoTyZbu4tQSL.jpg" alt="6 Inch Tin Shower" class="w-10 h-10 object-cover rounded-lg border border-emerald-400/50 flex-shrink-0 bg-white/10" onerror="this.src='/images/placeholder.jpg'">
                            <div class="min-w-0">
                                <div class="font-extrabold text-emerald-300 truncate">6&quot; Tin Shower</div>
                                <div class="text-[10px] text-gray-300">Worth <span class="text-amber-300 font-bold">₹200</span> (Free Gift)</div>
                            </div>
                        </div>

                        <!-- Prize 4: 5% Discount -->
                        <div class="bg-white/5 hover:bg-white/10 p-2 rounded-xl border border-amber-400/30 flex items-center gap-2.5 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-amber-500/20 border border-amber-400/50 flex items-center justify-center text-xl flex-shrink-0">
                                🏷️
                            </div>
                            <div class="min-w-0">
                                <div class="font-extrabold text-amber-300 truncate">5% Flat Off</div>
                                <div class="text-[10px] text-gray-300">Instant Cart Discount</div>
                            </div>
                        </div>
                    </div>

                    <!-- Prize 5 note -->
                    <div class="p-1.5 rounded-lg bg-white/5 text-[10px] text-gray-400 text-center border border-white/5">
                        Slice 5: 🌟 <em>Better Luck Next Time</em> (Festive greeting)
                    </div>
                </div>
            </div>

            <!-- Dynamic Cart Tracker Box -->
            <div class="p-3.5 sm:p-4 rounded-xl bg-gradient-to-r from-amber-500/10 via-purple-500/10 to-orange-500/10 border border-amber-400/30 space-y-2">
                <div class="flex items-center justify-between text-xs sm:text-sm font-extrabold">
                    <span class="flex items-center gap-1.5 text-amber-300">
                        <span>🛒</span> Your Estimate Cart:
                    </span>
                    <span id="popupCartTotalDisplay" class="text-white text-base font-black">₹0.00</span>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-black/40 rounded-full h-2.5 overflow-hidden border border-white/10">
                    <div id="popupCartProgressBar" class="h-full bg-gradient-to-r from-amber-400 to-orange-500 rounded-full transition-all duration-500" style="width: 0%;"></div>
                </div>

                <!-- Status Message -->
                <div id="popupCartStatusMsg" class="text-[11px] sm:text-xs font-semibold text-gray-300 flex items-center justify-between">
                    <span>Add ₹5,000.00 more to unlock!</span>
                    <span class="text-amber-400 font-bold">Goal: ₹5,000.00</span>
                </div>
            </div>

            <!-- 3 Simple Steps to Claim -->
            <div class="bg-black/20 rounded-xl p-3 border border-white/5">
                <h5 class="text-[11px] font-extrabold uppercase tracking-wider text-gray-300 mb-2">How It Works:</h5>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-[11px] text-gray-300">
                    <div class="flex items-start gap-2 bg-white/5 p-2 rounded-lg">
                        <span class="w-5 h-5 rounded-full bg-amber-400 text-purple-950 font-black flex items-center justify-center text-[10px] flex-shrink-0 mt-0.5">1</span>
                        <span>Add items to your Estimate Cart to reach ₹5,000.</span>
                    </div>
                    <div class="flex items-start gap-2 bg-white/5 p-2 rounded-lg">
                        <span class="w-5 h-5 rounded-full bg-amber-400 text-purple-950 font-black flex items-center justify-center text-[10px] flex-shrink-0 mt-0.5">2</span>
                        <span>Proceed to Smart Checkout; your Wheel unlocks!</span>
                    </div>
                    <div class="flex items-start gap-2 bg-white/5 p-2 rounded-lg">
                        <span class="w-5 h-5 rounded-full bg-amber-400 text-purple-950 font-black flex items-center justify-center text-[10px] flex-shrink-0 mt-0.5">3</span>
                        <span>Spin to claim your gift or 5% discount on the bill.</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modal Footer Actions -->
        <div class="px-5 py-3.5 bg-black/40 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 flex-shrink-0">
            <button type="button" onclick="closeLuckyWheelPopup()" class="w-full sm:w-auto px-4 py-2 text-xs font-bold text-gray-300 hover:text-white bg-white/10 hover:bg-white/15 rounded-xl transition-colors border border-white/10 text-center cursor-pointer">
                ← Continue Adding Products
            </button>

            <button type="button" onclick="handlePopupCheckout()" id="popupCheckoutBtn" class="w-full sm:w-auto px-5 py-2.5 text-xs sm:text-sm font-black bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-300 hover:to-orange-400 text-purple-950 rounded-xl transition-all shadow-lg hover:shadow-amber-500/30 flex items-center justify-center gap-1.5 cursor-pointer">
                <span>Proceed to Checkout</span>
                <span>🚀</span>
            </button>
        </div>
    </div>
</div>

<!-- Center-Screen Celebration Unlock Animation Overlay (> ₹5,000) -->
<div id="luckyWheelUnlockCelebration" class="fixed inset-0 z-[100] bg-black/85 backdrop-blur-md flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-500">
    <!-- Confetti Canvas -->
    <canvas id="unlockConfettiCanvas" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>

    <!-- Celebration Card Centered -->
    <div id="unlockCelebrationCard" class="relative max-w-lg w-full bg-gradient-to-b from-[#2A0845] via-[#1E093B] to-[#0D001A] rounded-3xl p-6 sm:p-8 text-center text-white border-2 border-amber-400 shadow-[0_0_80px_rgba(245,158,11,0.65)] transform scale-50 transition-all duration-500 overflow-hidden font-sans">
        
        <!-- Background Radial Burst Glows -->
        <div class="absolute -top-20 -left-20 w-48 h-48 bg-amber-500/25 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-48 h-48 bg-purple-500/35 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Close Button -->
        <button type="button" onclick="closeUnlockCelebration()" class="absolute top-4 right-4 text-gray-400 hover:text-white bg-white/10 hover:bg-white/20 w-8 h-8 rounded-full flex items-center justify-center transition-colors border border-white/20 cursor-pointer z-10" title="Close">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Big Animated Wheel Icon with Burst Ping Glow -->
        <div class="relative mx-auto w-24 h-24 sm:w-28 sm:h-28 flex items-center justify-center mb-3">
            <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-amber-400 to-yellow-200 opacity-30 animate-ping"></div>
            <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-tr from-amber-500 to-yellow-300 p-1 shadow-[0_0_35px_rgba(245,158,11,0.85)] border-2 border-white flex items-center justify-center">
                <span class="text-4xl sm:text-5xl animate-spin" style="animation-duration: 5s;">🎡</span>
            </div>
            <div class="absolute -bottom-1 bg-gradient-to-r from-emerald-500 to-green-600 text-white text-[10px] sm:text-xs font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider border border-white shadow">
                🔓 UNLOCKED!
            </div>
        </div>

        <!-- Congratulation Badges & Headlines -->
        <div class="space-y-1.5 mb-4">
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-amber-400/20 text-amber-300 text-[11px] sm:text-xs font-black uppercase tracking-widest border border-amber-400/40">
                <span>✨</span> ORDER ABOVE ₹5,000 QUALIFIED <span>✨</span>
            </div>
            
            <h2 class="text-2xl sm:text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-amber-200 to-orange-400 tracking-tight drop-shadow-md">
                🎉 YOU UNLOCKED THE LUCKY WHEEL! 🎉
            </h2>

            <p class="text-xs sm:text-sm text-gray-200 max-w-sm mx-auto leading-relaxed">
                Your Estimate Cart reached <strong class="text-amber-300 font-extrabold text-sm sm:text-base" id="unlockCartAmount">₹5,000.00</strong>! You've qualified for a guaranteed spin at Smart Checkout to win authentic cracker gifts or instant 5% off!
            </p>
        </div>

        <!-- Rewards Badge Strip -->
        <div class="bg-black/30 border border-amber-400/30 rounded-2xl p-2.5 sm:p-3 mb-5 grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-xs">
            <div class="p-1.5 rounded-xl bg-white/5 border border-rose-400/30">
                <div class="text-base">💥</div>
                <div class="text-[10px] font-bold text-rose-300 truncate">25 Raider</div>
                <div class="text-[9px] text-amber-300 font-semibold">₹220 Gift</div>
            </div>
            <div class="p-1.5 rounded-xl bg-white/5 border border-purple-400/30">
                <div class="text-base">🎆</div>
                <div class="text-[10px] font-bold text-purple-300 truncate">30 Shot Regular</div>
                <div class="text-[9px] text-amber-300 font-semibold">₹390 Gift</div>
            </div>
            <div class="p-1.5 rounded-xl bg-white/5 border border-emerald-400/30">
                <div class="text-base">🎇</div>
                <div class="text-[10px] font-bold text-emerald-300 truncate">6" Tin Shower</div>
                <div class="text-[9px] text-amber-300 font-semibold">₹200 Gift</div>
            </div>
            <div class="p-1.5 rounded-xl bg-white/5 border border-amber-400/30">
                <div class="text-base">🏷️</div>
                <div class="text-[10px] font-bold text-amber-300 truncate">5% Discount</div>
                <div class="text-[9px] text-amber-300 font-semibold">Flat Off Bill</div>
            </div>
        </div>

        <!-- Actions -->
        <div class="space-y-2">
            <button type="button" onclick="handleCelebrationCheckout()" class="w-full py-3 sm:py-3.5 bg-gradient-to-r from-emerald-400 via-green-500 to-emerald-600 hover:from-emerald-300 hover:to-green-400 text-purple-950 font-black text-sm sm:text-base rounded-xl transition-all shadow-[0_0_25px_rgba(16,185,129,0.5)] flex items-center justify-center gap-2 cursor-pointer transform hover:scale-[1.02] active:scale-[0.98]">
                <span>Proceed to Checkout &amp; Claim Spin</span>
                <span>🚀</span>
            </button>

            <div class="flex items-center justify-center gap-3 pt-1">
                <button type="button" onclick="viewWheelFromCelebration()" class="text-xs text-amber-300 hover:text-amber-200 font-bold underline cursor-pointer">
                    🎡 Preview Wheel &amp; Prizes
                </button>
                <span class="text-gray-500">•</span>
                <button type="button" onclick="closeUnlockCelebration()" class="text-xs text-gray-400 hover:text-gray-200 font-semibold cursor-pointer">
                    Continue Shopping
                </button>
            </div>
        </div>

    </div>
</div>

<!-- Floating Trigger Widget (Pinned Bottom-Left) -->
<div id="luckyWheelFloatingWidget" class="fixed bottom-4 left-4 z-40">
    <button type="button" onclick="openLuckyWheelPopup()" class="group relative flex items-center gap-2.5 bg-gradient-to-r from-amber-500 via-orange-500 to-purple-700 hover:from-amber-400 hover:to-purple-600 text-white px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-full shadow-2xl hover:shadow-[0_0_25px_rgba(245,158,11,0.6)] border-2 border-amber-300 transition-all duration-300 hover:scale-105 cursor-pointer">
        <span class="text-xl sm:text-2xl animate-spin" style="animation-duration: 8s;">🎡</span>
        <div class="text-left leading-tight pr-1">
            <div class="text-[9px] sm:text-[10px] uppercase font-black tracking-wider text-amber-200">Diwali Dhamaka</div>
            <div class="text-xs sm:text-sm font-black text-white">Spin &amp; Win (&gt;₹5k)</div>
        </div>
        <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-amber-400 border border-white"></span>
        </span>
    </button>
</div>

<!-- Lucky Wheel JavaScript Engine -->
<script>
(function() {
    // 5 Prize definitions matching checkout & stock
    const POPUP_PRIZES = [
        { name: 'Better Luck Next Time', color: '#4A154B', textColor: '#FDE047', icon: '🌟' },
        { name: '25 Raider', color: '#E11D48', textColor: '#FFFFFF', icon: '💥' },
        { name: '5% Discount', color: '#D97706', textColor: '#FFFFFF', icon: '🏷️' },
        { name: '30 Shot Regular', color: '#7C3AED', textColor: '#FFFFFF', icon: '🎆' },
        { name: '6" Tin Shower', color: '#059669', textColor: '#FFFFFF', icon: '🎇' },
    ];

    let currentRotation = 0;
    let isSpinning = false;
    let audioCtx = null;
    let confettiAnimationId = null;

    function getAudioContext() {
        if (!audioCtx) {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (AudioContext) audioCtx = new AudioContext();
        }
        if (audioCtx && audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        return audioCtx;
    }

    function playTick() {
        try {
            const ctx = getAudioContext();
            if (!ctx) return;
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(450, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(150, ctx.currentTime + 0.04);
            gain.gain.setValueAtTime(0.2, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.04);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.05);
        } catch(e) {}
    }

    function playFanfare() {
        try {
            const ctx = getAudioContext();
            if (!ctx) return;
            const notes = [523.25, 659.25, 783.99, 1046.50];
            notes.forEach((freq, idx) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, ctx.currentTime + idx * 0.09);
                gain.gain.setValueAtTime(0.3, ctx.currentTime + idx * 0.09);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + idx * 0.09 + 0.35);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(ctx.currentTime + idx * 0.09);
                osc.stop(ctx.currentTime + idx * 0.09 + 0.36);
            });
        } catch(e) {}
    }

    // Celebratory Fanfare on crossing ₹5,000
    function playCelebrationFanfare() {
        try {
            const ctx = getAudioContext();
            if (!ctx) return;
            // Triumphant Diwali chime fanfare
            const sequence = [
                { f: 523.25, t: 0.00, d: 0.15 },
                { f: 659.25, t: 0.12, d: 0.15 },
                { f: 783.99, t: 0.24, d: 0.20 },
                { f: 1046.50, t: 0.40, d: 0.60 },
                { f: 1318.51, t: 0.55, d: 0.80 },
            ];
            sequence.forEach(item => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(item.f, ctx.currentTime + item.t);
                gain.gain.setValueAtTime(0.35, ctx.currentTime + item.t);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + item.t + item.d);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(ctx.currentTime + item.t);
                osc.stop(ctx.currentTime + item.t + item.d + 0.05);
            });
        } catch(e) {}
    }

    // Confetti Engine
    function startConfetti() {
        const canvas = document.getElementById('unlockConfettiCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const colors = ['#F59E0B', '#EF4444', '#10B981', '#8B5CF6', '#3B82F6', '#EC4899', '#FBBF24'];
        const particles = [];
        const count = 130;

        for (let i = 0; i < count; i++) {
            particles.push({
                x: canvas.width / 2 + (Math.random() - 0.5) * 100,
                y: canvas.height / 2 + (Math.random() - 0.5) * 50,
                vx: (Math.random() - 0.5) * 16,
                vy: (Math.random() - 0.7) * 18 - 3,
                size: Math.random() * 8 + 4,
                color: colors[Math.floor(Math.random() * colors.length)],
                rotation: Math.random() * 360,
                rotationSpeed: (Math.random() - 0.5) * 12,
                opacity: 1,
                decay: Math.random() * 0.004 + 0.003
            });
        }

        function renderConfetti() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            let alive = 0;

            particles.forEach(p => {
                if (p.opacity > 0) {
                    alive++;
                    p.x += p.vx;
                    p.y += p.vy;
                    p.vy += 0.25; // gravity
                    p.vx *= 0.98; // air drag
                    p.rotation += p.rotationSpeed;
                    p.opacity -= p.decay;

                    ctx.save();
                    ctx.translate(p.x, p.y);
                    ctx.rotate((p.rotation * Math.PI) / 180);
                    ctx.globalAlpha = Math.max(0, p.opacity);
                    ctx.fillStyle = p.color;
                    ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size * 0.7);
                    ctx.restore();
                }
            });

            if (alive > 0) {
                confettiAnimationId = requestAnimationFrame(renderConfetti);
            }
        }

        if (confettiAnimationId) cancelAnimationFrame(confettiAnimationId);
        confettiAnimationId = requestAnimationFrame(renderConfetti);
    }

    function stopConfetti() {
        if (confettiAnimationId) {
            cancelAnimationFrame(confettiAnimationId);
            confettiAnimationId = null;
        }
        const canvas = document.getElementById('unlockConfettiCanvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    }

    // Trigger the Center Unlock Celebration
    window.triggerLuckyWheelUnlockCelebration = function(totalAmount) {
        const overlay = document.getElementById('luckyWheelUnlockCelebration');
        const card = document.getElementById('unlockCelebrationCard');
        const amountEl = document.getElementById('unlockCartAmount');
        if (!overlay || !card) return;

        if (amountEl) {
            const amt = parseFloat(totalAmount || window.getPopupCartTotal() || 5000);
            amountEl.textContent = '₹' + amt.toFixed(2);
        }

        // Close standard pop-up if currently open
        window.closeLuckyWheelPopup();

        // Show celebration overlay
        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100');
        card.classList.remove('scale-50');
        card.classList.add('scale-100');
        document.body.style.overflow = 'hidden';

        playCelebrationFanfare();
        startConfetti();
    };

    window.closeUnlockCelebration = function() {
        const overlay = document.getElementById('luckyWheelUnlockCelebration');
        const card = document.getElementById('unlockCelebrationCard');
        if (!overlay || !card) return;

        overlay.classList.remove('opacity-100');
        overlay.classList.add('opacity-0', 'pointer-events-none');
        card.classList.remove('scale-100');
        card.classList.add('scale-50');
        document.body.style.overflow = '';
        stopConfetti();
    };

    window.viewWheelFromCelebration = function() {
        window.closeUnlockCelebration();
        setTimeout(() => {
            window.openLuckyWheelPopup();
        }, 200);
    };

    window.handleCelebrationCheckout = function() {
        window.closeUnlockCelebration();
        if (typeof proceedToCheckout === 'function') {
            proceedToCheckout();
        } else {
            window.location.href = "{{ route('smart-checkout.show') }}";
        }
    };

    // Draw Wheel on Canvas
    window.drawPopupWheel = function(rotationAngle = 0) {
        const canvas = document.getElementById('popupWheelCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const numSlices = POPUP_PRIZES.length;
        const sliceAngle = (2 * Math.PI) / numSlices;
        const radius = canvas.width / 2;
        const cx = radius;
        const cy = radius;

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Draw Slices
        for (let i = 0; i < numSlices; i++) {
            const angleStart = rotationAngle + (i * sliceAngle);
            const angleEnd = angleStart + sliceAngle;
            const prize = POPUP_PRIZES[i];

            ctx.beginPath();
            ctx.moveTo(cx, cy);
            ctx.arc(cx, cy, radius - 4, angleStart, angleEnd);
            ctx.closePath();
            ctx.fillStyle = prize.color;
            ctx.fill();

            // Inner slice shadow/border
            ctx.lineWidth = 1.5;
            ctx.strokeStyle = '#FDE047';
            ctx.stroke();

            // Text / Label
            ctx.save();
            ctx.translate(cx, cy);
            ctx.rotate(angleStart + sliceAngle / 2);
            ctx.textAlign = 'right';
            ctx.fillStyle = prize.textColor;
            ctx.font = 'bold 11px system-ui, sans-serif';
            ctx.shadowColor = 'rgba(0,0,0,0.8)';
            ctx.shadowBlur = 4;
            ctx.fillText(prize.icon + ' ' + prize.name, radius - 16, 4);
            ctx.restore();
        }

        // Center hub border
        ctx.beginPath();
        ctx.arc(cx, cy, 32, 0, 2 * Math.PI);
        ctx.fillStyle = '#1E093B';
        ctx.fill();
        ctx.lineWidth = 3;
        ctx.strokeStyle = '#FDE047';
        ctx.stroke();
    };

    // Test Spin (Simulation Preview)
    window.testSpinPopupWheel = function() {
        if (isSpinning) return;
        isSpinning = true;
        const spinBtn = document.getElementById('popupSpinBtn');
        if (spinBtn) spinBtn.disabled = true;

        // Choose a winning slice for preview
        const targetSlice = Math.floor(Math.random() * POPUP_PRIZES.length);
        const sliceAngle = (2 * Math.PI) / POPUP_PRIZES.length;
        const fullRotations = 4 + Math.floor(Math.random() * 2);
        // Arrow is at top (3*PI/2)
        const targetAngle = (3 * Math.PI / 2) - (targetSlice * sliceAngle + sliceAngle / 2);
        const totalRotation = (fullRotations * 2 * Math.PI) + targetAngle;

        const startAngle = currentRotation;
        const delta = totalRotation - (startAngle % (2 * Math.PI));

        const duration = 3500;
        const startTime = performance.now();
        let lastTickAngle = startAngle;

        function animate(now) {
            const elapsed = now - startTime;
            const progress = Math.min(1, elapsed / duration);
            // Ease out cubic
            const easeOut = 1 - Math.pow(1 - progress, 3);
            currentRotation = startAngle + (delta * easeOut);

            // Tick sound on slice crossing
            const currentSliceIndex = Math.floor((currentRotation % (2 * Math.PI)) / sliceAngle);
            const lastSliceIndex = Math.floor((lastTickAngle % (2 * Math.PI)) / sliceAngle);
            if (currentSliceIndex !== lastSliceIndex) {
                playTick();
                lastTickAngle = currentRotation;
            }

            window.drawPopupWheel(currentRotation);

            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                isSpinning = false;
                if (spinBtn) spinBtn.disabled = false;
                playFanfare();
                const won = POPUP_PRIZES[targetSlice];
                alert('🎡 Test Spin Result: ' + won.icon + ' ' + won.name + '!\n\nReach ₹5,000 in your Estimate Cart to claim real rewards on checkout!');
            }
        }

        requestAnimationFrame(animate);
    };

    // Calculate current cart total from localStorage
    window.getPopupCartTotal = function() {
        try {
            const raw = localStorage.getItem('cartItems');
            const cart = raw ? JSON.parse(raw) : [];
            let subtotal = 0;
            if (Array.isArray(cart)) {
                cart.forEach(item => {
                    if (item.is_lucky_spin_gift || item.is_free_gift) return;
                    const rate = parseFloat(item.rate || item.price || 0);
                    const qty = parseInt(item.quantity || 0);
                    subtotal += rate * qty;
                });
            }
            return subtotal;
        } catch(e) {
            return 0;
        }
    };

    // Update Cart Tracker in Popup & Check for ₹5,000 Unlock Milestone
    window.syncPopupCartStatus = function() {
        const total = window.getPopupCartTotal();
        const displayEl = document.getElementById('popupCartTotalDisplay');
        const barEl = document.getElementById('popupCartProgressBar');
        const msgEl = document.getElementById('popupCartStatusMsg');
        const checkoutBtn = document.getElementById('popupCheckoutBtn');

        if (displayEl) displayEl.textContent = '₹' + total.toFixed(2);

        const target = 5000;
        const percentage = Math.min(100, Math.round((total / target) * 100));

        if (barEl) barEl.style.width = percentage + '%';

        if (msgEl) {
            if (total >= target) {
                msgEl.innerHTML = '<span class="text-emerald-300 font-bold">🎉 UNLOCKED! Your order qualifies for a Free Spin at checkout!</span>';
                if (barEl) barEl.className = 'h-full bg-gradient-to-r from-emerald-400 to-green-500 rounded-full transition-all duration-500';
            } else {
                const diff = (target - total).toFixed(2);
                msgEl.innerHTML = `<span>Add <strong class="text-amber-300">₹${diff}</strong> more to unlock!</span> <span class="text-amber-400 font-bold">${percentage}% of ₹5,000</span>`;
                if (barEl) barEl.className = 'h-full bg-gradient-to-r from-amber-400 to-orange-500 rounded-full transition-all duration-500';
            }
        }

        if (checkoutBtn) {
            if (total >= target) {
                checkoutBtn.innerHTML = '<span>Proceed to Checkout &amp; Claim Prize</span> <span>🚀</span>';
                checkoutBtn.className = 'w-full sm:w-auto px-5 py-2.5 text-xs sm:text-sm font-black bg-gradient-to-r from-emerald-400 to-green-500 hover:from-emerald-300 hover:to-green-400 text-purple-950 rounded-xl transition-all shadow-lg hover:shadow-emerald-500/30 flex items-center justify-center gap-1.5 cursor-pointer animate-pulse';
            } else {
                checkoutBtn.innerHTML = '<span>Proceed to Checkout</span> <span>👉</span>';
                checkoutBtn.className = 'w-full sm:w-auto px-5 py-2.5 text-xs sm:text-sm font-black bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-300 hover:to-orange-400 text-purple-950 rounded-xl transition-all shadow-lg hover:shadow-amber-500/30 flex items-center justify-center gap-1.5 cursor-pointer';
            }
        }

        // TRIGGER CENTER CELEBRATION WHEN TOTAL CROSSES ₹5,000
        let celebrated = false;
        try {
            celebrated = sessionStorage.getItem('luckyWheelUnlockedCelebrated') === '1';
        } catch(e) {}

        if (total >= target && !celebrated) {
            try { sessionStorage.setItem('luckyWheelUnlockedCelebrated', '1'); } catch(e) {}
            // Small delay so DOM updates nicely
            setTimeout(() => {
                window.triggerLuckyWheelUnlockCelebration(total);
            }, 350);
        } else if (total < target) {
            // Reset if cart drops below 5k, so crossing 5k again triggers the celebration
            try { sessionStorage.removeItem('luckyWheelUnlockedCelebrated'); } catch(e) {}
            try { sessionStorage.removeItem('lucky_spin_result'); } catch(e) {}
            try {
                const raw = localStorage.getItem('cartItems');
                let cart = raw ? JSON.parse(raw) : [];
                if (Array.isArray(cart) && cart.some(it => it.is_lucky_spin_gift)) {
                    cart = cart.filter(it => !it.is_lucky_spin_gift);
                    localStorage.setItem('cartItems', JSON.stringify(cart));
                }
            } catch(e) {}
        }
    };

    // Open & Close Modal Logic
    window.openLuckyWheelPopup = function() {
        const backdrop = document.getElementById('luckyWheelPopupBackdrop');
        const dialog = document.getElementById('luckyWheelPopupDialog');
        if (!backdrop || !dialog) return;

        window.syncPopupCartStatus();
        window.drawPopupWheel(currentRotation);

        backdrop.classList.remove('opacity-0', 'pointer-events-none');
        backdrop.classList.add('opacity-100');
        dialog.classList.remove('scale-90');
        dialog.classList.add('scale-100');
        document.body.style.overflow = 'hidden';
    };

    window.closeLuckyWheelPopup = function() {
        const backdrop = document.getElementById('luckyWheelPopupBackdrop');
        const dialog = document.getElementById('luckyWheelPopupDialog');
        if (!backdrop || !dialog) return;

        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0', 'pointer-events-none');
        dialog.classList.remove('scale-100');
        dialog.classList.add('scale-90');
        document.body.style.overflow = '';
        try { sessionStorage.setItem('luckyWheelDismissed', '1'); } catch(e) {}
    };

    window.handlePopupCheckout = function() {
        window.closeLuckyWheelPopup();
        if (typeof proceedToCheckout === 'function') {
            proceedToCheckout();
        } else {
            window.location.href = "{{ route('smart-checkout.show') }}";
        }
    };

    // Close on backdrop click
    document.addEventListener('DOMContentLoaded', function() {
        const backdrop = document.getElementById('luckyWheelPopupBackdrop');
        if (backdrop) {
            backdrop.addEventListener('click', function(e) {
                if (e.target === this) {
                    window.closeLuckyWheelPopup();
                }
            });
        }

        const celebrationOverlay = document.getElementById('luckyWheelUnlockCelebration');
        if (celebrationOverlay) {
            celebrationOverlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    window.closeUnlockCelebration();
                }
            });
        }

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const bd = document.getElementById('luckyWheelPopupBackdrop');
                if (bd && !bd.classList.contains('pointer-events-none')) {
                    window.closeLuckyWheelPopup();
                }
                const cel = document.getElementById('luckyWheelUnlockCelebration');
                if (cel && !cel.classList.contains('pointer-events-none')) {
                    window.closeUnlockCelebration();
                }
            }
        });

        // Initialize Canvas
        setTimeout(() => {
            window.drawPopupWheel(0);
            window.syncPopupCartStatus();
        }, 300);

        // Auto Pop-up teaser after 1.2s only if cart is < 5000 and not dismissed
        setTimeout(() => {
            let dismissed = false;
            try { dismissed = sessionStorage.getItem('luckyWheelDismissed') === '1'; } catch(e) {}
            const currentTotal = window.getPopupCartTotal();
            if (!dismissed && currentTotal < 5000) {
                window.openLuckyWheelPopup();
            }
        }, 1200);
    });

    // Hook into global cart change listeners if available
    window.addEventListener('storage', function(e) {
        if (e.key === 'cartItems') {
            window.syncPopupCartStatus();
        }
    });

})();
</script>
