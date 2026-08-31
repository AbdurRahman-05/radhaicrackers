@php
    try {
        $videoEnabled = (bool) \DB::table('settings')->where('key', 'popup_video_enabled')->value('value');
        $videoUrl = \DB::table('settings')->where('key', 'popup_video_url')->value('value') ?: '';
        $videoTitle = \DB::table('settings')->where('key', 'popup_video_title')->value('value') ?: '✨ Radhe Crackers - Festival Specials';
        $videoAutoplay = (bool) (\DB::table('settings')->where('key', 'popup_video_autoplay')->value('value') ?? true);
        $videoMuted = (bool) (\DB::table('settings')->where('key', 'popup_video_muted')->value('value') ?? true);
        $videoFrequency = \DB::table('settings')->where('key', 'popup_video_frequency')->value('value') ?: 'once_per_session';
        $videoShowOn = \DB::table('settings')->where('key', 'popup_video_show_on')->value('value') ?: 'home';
        $videoCtaText = \DB::table('settings')->where('key', 'popup_video_cta_text')->value('value') ?: '🔥 Explore Products & Offers';
        $videoCtaUrl = \DB::table('settings')->where('key', 'popup_video_cta_url')->value('value') ?: '/sale-products';
    } catch (\Exception $e) {
        $videoEnabled = false;
        $videoUrl = '';
        $videoTitle = 'Radhe Crackers';
        $videoAutoplay = true;
        $videoMuted = true;
        $videoFrequency = 'once_per_session';
        $videoShowOn = 'home';
        $videoCtaText = 'Explore Products';
        $videoCtaUrl = '/sale-products';
    }

    $isTargetPage = ($videoShowOn === 'all') || request()->is('/') || request()->routeIs('home');
@endphp

@if($videoEnabled && !empty($videoUrl) && $isTargetPage)
<!-- Site Opening Video Popup Component -->
<div id="siteOpeningVideoModal" 
     class="fixed inset-0 z-[99999] flex items-center justify-center p-3 sm:p-6 opacity-0 pointer-events-none transition-all duration-500 ease-out"
     role="dialog" 
     aria-modal="true"
     aria-labelledby="videoModalTitle">

    <!-- Blurred Dark Backdrop -->
    <div id="videoModalBackdrop" 
         class="fixed inset-0 bg-black/85 backdrop-blur-md transition-opacity duration-500"></div>

    <!-- Modal Card Container with Glowing Gold Aura -->
    <div id="videoModalCard" 
         class="relative w-full max-w-3xl bg-gradient-to-b from-[#2B0E4E] via-[#1E093B] to-[#120424] rounded-2xl sm:rounded-3xl border-2 border-yellow-400/80 shadow-[0_0_50px_rgba(234,179,8,0.35)] overflow-hidden transform scale-90 transition-all duration-500 ease-out z-10">

        <!-- Top Decorative Festive Glow Strip -->
        <div class="h-1.5 w-full bg-gradient-to-r from-yellow-400 via-amber-300 to-yellow-500 animate-pulse"></div>

        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 sm:px-6 py-3.5 bg-black/30 border-b border-yellow-500/20">
            <div class="flex items-center gap-2.5 min-w-0 pr-2">
                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-r from-yellow-400 to-amber-500 text-purple-950 flex items-center justify-center shadow-md font-bold text-sm">
                    🎇
                </span>
                <h3 id="videoModalTitle" class="text-sm sm:text-base md:text-lg font-black text-white truncate tracking-wide drop-shadow">
                    {{ $videoTitle }}
                </h3>
            </div>

            <!-- Close Button (X) -->
            <button id="closeVideoModalBtn" 
                    type="button"
                    aria-label="Close Video Popup"
                    class="flex-shrink-0 w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/10 hover:bg-red-600 text-gray-200 hover:text-white flex items-center justify-center transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 cursor-pointer text-xl sm:text-2xl font-bold leading-none shadow-lg">
                &times;
            </button>
        </div>

        <!-- Video Player Section (16:9 Aspect Ratio) -->
        <div class="relative w-full bg-black aspect-video overflow-hidden">
            <!-- Loading Spinner Placeholder -->
            <div id="videoLoadingPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-950 text-yellow-400 z-0">
                <svg class="animate-spin h-10 w-10 text-yellow-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span class="text-xs font-semibold text-gray-300">Loading Video...</span>
            </div>

            <!-- YouTube Iframe (Source is loaded dynamically via JS for fast page load & clean audio stop) -->
            <iframe id="videoPopupIframe" 
                    class="relative z-10 w-full h-full border-0" 
                    src=""
                    title="Video Player"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    allowfullscreen>
            </iframe>
        </div>

        <!-- Modal Footer Actions -->
        <div class="px-4 sm:px-6 py-3.5 bg-black/40 border-t border-yellow-500/20 flex flex-col sm:flex-row items-center justify-between gap-3">
            <!-- Don't show again checkbox / Quick notice -->
            <label class="flex items-center gap-2 text-xs text-gray-300 hover:text-white cursor-pointer select-none">
                <input type="checkbox" id="dontShowAgainCheckbox" class="w-4 h-4 rounded text-yellow-500 focus:ring-yellow-400 border-gray-600 bg-gray-900 cursor-pointer">
                <span>Don't show this video again today</span>
            </label>

            <!-- Buttons -->
            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                <button id="dismissVideoBtn" 
                        type="button" 
                        class="px-4 py-2 rounded-xl text-xs font-bold text-gray-300 hover:text-white bg-white/10 hover:bg-white/20 transition cursor-pointer">
                    Close
                </button>
                @if(!empty($videoCtaText))
                <a href="{{ $videoCtaUrl }}" 
                   class="inline-flex items-center justify-center gap-1.5 px-5 py-2 rounded-xl text-xs sm:text-sm font-black text-purple-950 bg-gradient-to-r from-yellow-400 via-amber-400 to-yellow-500 hover:from-yellow-300 hover:to-amber-400 shadow-[0_0_15px_rgba(234,179,8,0.5)] hover:scale-105 transition-all duration-200">
                    {{ $videoCtaText }} &rarr;
                </a>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- Modal Logic Script -->
<script>
(function() {
    const rawVideoUrl = @json($videoUrl);
    const frequency = @json($videoFrequency);
    const autoplay = @json($videoAutoplay);
    const muted = @json($videoMuted);

    // Helper: Parse YouTube embed URL from various formats
    function parseYouTubeEmbedUrl(url, auto, mute) {
        if (!url) return '';
        url = url.trim();
        let videoId = '';

        // 1. YouTube Shorts (e.g. youtube.com/shorts/VIDEO_ID)
        const shortsMatch = url.match(/youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/i);
        if (shortsMatch && shortsMatch[1]) {
            videoId = shortsMatch[1];
        } 
        // 2. Standard Watch or youtu.be or embed
        else {
            const standardMatch = url.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/i);
            if (standardMatch && standardMatch[1]) {
                videoId = standardMatch[1];
            } else if (/^[a-zA-Z0-9_-]{11}$/.test(url)) {
                videoId = url;
            }
        }

        if (videoId) {
            const params = new URLSearchParams({
                enablejsapi: '1',
                rel: '0',
                modestbranding: '1',
                playsinline: '1'
            });
            if (auto) params.set('autoplay', '1');
            if (mute) params.set('mute', '1');
            return 'https://www.youtube.com/embed/' + videoId + '?' + params.toString();
        }

        return url;
    }

    // Storage Key Constants
    const SESSION_KEY = 'radhe_popup_video_seen_session';
    const DAY_KEY = 'radhe_popup_video_last_seen_day';
    const DONT_SHOW_KEY = 'radhe_popup_video_dont_show_date';

    // Frequency Validator
    function shouldShowVideoPopup() {
        const todayStr = new Date().toDateString();

        // 1. Check if user explicitly selected "Don't show again today"
        const dontShowDate = localStorage.getItem(DONT_SHOW_KEY);
        if (dontShowDate === todayStr) {
            return false;
        }

        // 2. Check configured frequency
        if (frequency === 'always') {
            return true;
        } else if (frequency === 'once_per_session') {
            return !sessionStorage.getItem(SESSION_KEY);
        } else if (frequency === 'once_per_day') {
            const lastSeen = localStorage.getItem(DAY_KEY);
            return lastSeen !== todayStr;
        }

        return true;
    }

    // Modal Controller
    function initVideoModal() {
        const modal = document.getElementById('siteOpeningVideoModal');
        const card = document.getElementById('videoModalCard');
        const iframe = document.getElementById('videoPopupIframe');
        const closeBtn = document.getElementById('closeVideoModalBtn');
        const dismissBtn = document.getElementById('dismissVideoBtn');
        const backdrop = document.getElementById('videoModalBackdrop');
        const dontShowCheckbox = document.getElementById('dontShowAgainCheckbox');

        if (!modal || !iframe) return;

        function openModal() {
            const embedSrc = parseYouTubeEmbedUrl(rawVideoUrl, autoplay, muted);
            iframe.src = embedSrc;

            // Animate In
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100', 'pointer-events-auto');
            if (card) {
                card.classList.remove('scale-90');
                card.classList.add('scale-100');
            }

            // Set session/day indicators
            const todayStr = new Date().toDateString();
            sessionStorage.setItem(SESSION_KEY, 'true');
            localStorage.setItem(DAY_KEY, todayStr);
        }

        function closeModal() {
            // Immediately stop audio/video by clearing iframe source
            iframe.src = '';

            // If user checked "Don't show again today"
            if (dontShowCheckbox && dontShowCheckbox.checked) {
                localStorage.setItem(DONT_SHOW_KEY, new Date().toDateString());
            }

            // Animate Out
            modal.classList.remove('opacity-100', 'pointer-events-auto');
            modal.classList.add('opacity-0', 'pointer-events-none');
            if (card) {
                card.classList.remove('scale-100');
                card.classList.add('scale-90');
            }
        }

        // Event Listeners
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (dismissBtn) dismissBtn.addEventListener('click', closeModal);
        if (backdrop) backdrop.addEventListener('click', closeModal);

        // Escape Key Support
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('pointer-events-none')) {
                closeModal();
            }
        });

        // Trigger opening with gentle 600ms delay after DOM is ready
        if (shouldShowVideoPopup()) {
            setTimeout(openModal, 600);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initVideoModal);
    } else {
        initVideoModal();
    }
})();
</script>
@endif
