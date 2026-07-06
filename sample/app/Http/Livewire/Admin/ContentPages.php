<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class ContentPages extends Component
{
    public $activeTab = 'about';
    public $aboutContent = '';
    public $contactContent = '';
    public $paymentOptionsContent = '';
    public $privacyContent = '';
    public $termsContent = '';
    
    public $businessName = '';
    public $businessEmail = '';
    public $businessPhone = '';
    public $businessAddress = '';
    public $upiId = '';

    protected $rules = [
        'aboutContent' => 'required|string|max:10000',
        'contactContent' => 'required|string|max:10000',
        'paymentOptionsContent' => 'required|string|max:10000',
        'privacyContent' => 'required|string|max:10000',
        'termsContent' => 'required|string|max:10000',
        'businessName' => 'required|string|max:255',
        'businessEmail' => 'required|email|max:255',
        'businessPhone' => 'required|string|max:20',
        'businessAddress' => 'required|string|max:500',
        'upiId' => 'required|string|max:100',
    ];

    public function mount()
    {
        $this->loadContent();
    }

    public function loadContent()
    {
        // Load content from storage or database
        $this->aboutContent = $this->getContent('about');
        $this->contactContent = $this->getContent('contact');
        $this->paymentOptionsContent = $this->getContent('payment_options');
        $this->privacyContent = $this->getContent('privacy');
        $this->termsContent = $this->getContent('terms');
        
        // Load business settings
        $this->businessName = $this->getSetting('business_name', 'Cracker Store');
        $this->businessEmail = $this->getSetting('business_email', 'info@crackerstore.com');
        $this->businessPhone = $this->getSetting('business_phone', '+91 9876543210');
        $this->businessAddress = $this->getSetting('business_address', 'Your Business Address');
        $this->upiId = $this->getSetting('upi_id', 'your-upi@paytm');
    }

    private function getContent($page)
    {
        $path = "content/{$page}.html";
        if (Storage::exists($path)) {
            return Storage::get($path);
        }
        
        // Return default content
        $defaults = [
            'about' => '<h2>About Us</h2><p>Welcome to our cracker store. We provide the best quality crackers for all occasions.</p>',
            'contact' => '<h2>Contact Us</h2><p>Get in touch with us for any queries or support.</p>',
            'payment_options' => '<h2>Payment Options</h2><p>We accept various payment methods including UPI, cards, and cash.</p>',
            'privacy' => '<h2>Privacy Policy</h2><p>Your privacy is important to us. Read our privacy policy here.</p>',
            'terms' => '<h2>Terms & Conditions</h2><p>Please read our terms and conditions carefully.</p>'
        ];
        
        return $defaults[$page] ?? '';
    }

    private function getSetting($key, $default = '')
    {
        return \DB::table('settings')->where('key', $key)->value('value') ?? $default;
    }

    private function setSetting($key, $value)
    {
        \DB::table('settings')->updateOrInsert(
            ['key' => $key],
            ['value' => $value, 'updated_at' => now()]
        );
    }

    public function saveAbout()
    {
        $this->validate([
            'aboutContent' => 'required|string|max:10000'
        ]);

        Storage::put('content/about.html', $this->aboutContent);
        session()->flash('success', 'About page content saved successfully!');
    }

    public function saveContact()
    {
        $this->validate([
            'contactContent' => 'required|string|max:10000'
        ]);

        Storage::put('content/contact.html', $this->contactContent);
        session()->flash('success', 'Contact page content saved successfully!');
    }

    public function savePaymentOptions()
    {
        $this->validate([
            'paymentOptionsContent' => 'required|string|max:10000'
        ]);

        Storage::put('content/payment_options.html', $this->paymentOptionsContent);
        session()->flash('success', 'Payment options content saved successfully!');
    }

    public function savePrivacy()
    {
        $this->validate([
            'privacyContent' => 'required|string|max:10000'
        ]);

        Storage::put('content/privacy.html', $this->privacyContent);
        session()->flash('success', 'Privacy policy saved successfully!');
    }

    public function saveTerms()
    {
        $this->validate([
            'termsContent' => 'required|string|max:10000'
        ]);

        Storage::put('content/terms.html', $this->termsContent);
        session()->flash('success', 'Terms & conditions saved successfully!');
    }

    public function saveBusinessSettings()
    {
        $this->validate([
            'businessName' => 'required|string|max:255',
            'businessEmail' => 'required|email|max:255',
            'businessPhone' => 'required|string|max:20',
            'businessAddress' => 'required|string|max:500',
            'upiId' => 'required|string|max:100',
        ]);

        $this->setSetting('business_name', $this->businessName);
        $this->setSetting('business_email', $this->businessEmail);
        $this->setSetting('business_phone', $this->businessPhone);
        $this->setSetting('business_address', $this->businessAddress);
        $this->setSetting('upi_id', $this->upiId);

        session()->flash('success', 'Business settings saved successfully!');
    }

    public function previewPage($page)
    {
        $content = $this->getContent($page);
        $this->dispatch('showPreview', content: $content, title: ucfirst($page));
    }

    public function render()
    {
        return view('livewire.admin.content-pages');
    }
} 