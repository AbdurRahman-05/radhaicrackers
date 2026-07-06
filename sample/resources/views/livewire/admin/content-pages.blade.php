<div>
@php /* Start of single root element for Livewire */ @endphp
<div class="p-6 bg-white rounded-lg shadow-md">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Content Management</h2>
            <p class="text-gray-600">Manage static page content and business settings</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button wire:click="$set('activeTab', 'about')" class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'about' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                About Us
            </button>
            <button wire:click="$set('activeTab', 'contact')" class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'contact' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Contact
            </button>
            <button wire:click="$set('activeTab', 'payment_options')" class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'payment_options' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Payment Options
            </button>
            <button wire:click="$set('activeTab', 'privacy')" class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'privacy' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Privacy Policy
            </button>
            <button wire:click="$set('activeTab', 'terms')" class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'terms' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Terms & Conditions
            </button>
            <button wire:click="$set('activeTab', 'business')" class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'business' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Business Settings
            </button>
        </nav>
    </div>

    <!-- About Us Tab -->
    @if($activeTab === 'about')
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">About Us Content</label>
            <textarea wire:model="aboutContent" rows="10" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter about us content..."></textarea>
            <p class="text-xs text-gray-500 mt-1">You can use HTML tags for formatting</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="saveAbout" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Save About Us</button>
            <button wire:click="previewPage('about')" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Preview</button>
        </div>
    </div>
    @endif

    <!-- Contact Tab -->
    @if($activeTab === 'contact')
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Information</label>
            <textarea wire:model="contactContent" rows="10" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter contact information..."></textarea>
            <p class="text-xs text-gray-500 mt-1">You can use HTML tags for formatting</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="saveContact" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Save Contact</button>
            <button wire:click="previewPage('contact')" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Preview</button>
        </div>
    </div>
    @endif

    <!-- Payment Options Tab -->
    @if($activeTab === 'payment_options')
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Options</label>
            <textarea wire:model="paymentOptionsContent" rows="10" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter payment options information..."></textarea>
            <p class="text-xs text-gray-500 mt-1">You can use HTML tags for formatting</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="savePaymentOptions" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Save Payment Options</button>
            <button wire:click="previewPage('payment_options')" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Preview</button>
        </div>
    </div>
    @endif

    <!-- Privacy Policy Tab -->
    @if($activeTab === 'privacy')
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Privacy Policy</label>
            <textarea wire:model="privacyContent" rows="10" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter privacy policy..."></textarea>
            <p class="text-xs text-gray-500 mt-1">You can use HTML tags for formatting</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="savePrivacy" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Save Privacy Policy</button>
            <button wire:click="previewPage('privacy')" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Preview</button>
        </div>
    </div>
    @endif

    <!-- Terms & Conditions Tab -->
    @if($activeTab === 'terms')
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
            <textarea wire:model="termsContent" rows="10" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter terms and conditions..."></textarea>
            <p class="text-xs text-gray-500 mt-1">You can use HTML tags for formatting</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="saveTerms" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Save Terms & Conditions</button>
            <button wire:click="previewPage('terms')" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Preview</button>
        </div>
    </div>
    @endif

    <!-- Business Settings Tab -->
    @if($activeTab === 'business')
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Business Name</label>
                <input wire:model="businessName" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Business Email</label>
                <input wire:model="businessEmail" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Business Phone</label>
                <input wire:model="businessPhone" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">UPI ID</label>
                <input wire:model="upiId" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Business Address</label>
            <textarea wire:model="businessAddress" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
        <div>
            <button wire:click="saveBusinessSettings" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Save Business Settings</button>
        </div>
    </div>
    @endif
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="previewTitle">Preview</h3>
                <button onclick="document.getElementById('previewModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="previewContent" class="prose max-w-none">
                <!-- Preview content will be inserted here -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('showPreview', (event) => {
        document.getElementById('previewTitle').textContent = event.title;
        document.getElementById('previewContent').innerHTML = event.content;
        document.getElementById('previewModal').classList.remove('hidden');
    });
});
</script>
@php /* End of single root element for Livewire */ @endphp
</div>
</div> 