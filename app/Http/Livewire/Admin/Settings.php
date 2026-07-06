<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class Settings extends Component
{
    public $businessName = '';
    public $businessEmail = '';
    public $businessPhone = '';
    public $businessAddress = '';
    public $upiId = '';
    public $otpExpiryMinutes = 5;
    public $stockReleaseInterval = 10;
    public $stockReleaseQuantity = 10;
    public $stockExpiryMinutes = 10;
    public $whatsappBusinessNumber = '';
    public $enableAutoRelease = true;
    public $enableAutoExpiry = true;
    public $enableWhatsAppLogs = true;

    protected $rules = [
        'businessName' => 'required|string|max:255',
        'businessEmail' => 'required|email|max:255',
        'businessPhone' => 'required|string|max:20',
        'businessAddress' => 'required|string|max:500',
        'upiId' => 'required|string|max:100',
        'otpExpiryMinutes' => 'required|integer|min:1|max:60',
        'stockReleaseInterval' => 'required|integer|min:1|max:60',
        'stockReleaseQuantity' => 'required|integer|min:1|max:100',
        'stockExpiryMinutes' => 'required|integer|min:1|max:60',
        'whatsappBusinessNumber' => 'nullable|string|max:20',
        'enableAutoRelease' => 'boolean',
        'enableAutoExpiry' => 'boolean',
        'enableWhatsAppLogs' => 'boolean',
    ];

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->businessName = $this->getSetting('business_name', 'Cracker Store');
        $this->businessEmail = $this->getSetting('business_email', 'info@crackerstore.com');
        $this->businessPhone = $this->getSetting('business_phone', '+91 9876543210');
        $this->businessAddress = $this->getSetting('business_address', 'Your Business Address');
        $this->upiId = $this->getSetting('upi_id', 'your-upi@paytm');
        $this->otpExpiryMinutes = (int) $this->getSetting('otp_expiry_minutes', 5);
        $this->stockReleaseInterval = (int) $this->getSetting('stock_release_interval', 10);
        $this->stockReleaseQuantity = (int) $this->getSetting('stock_release_quantity', 10);
        $this->stockExpiryMinutes = (int) $this->getSetting('stock_expiry_minutes', 10);
        $this->whatsappBusinessNumber = $this->getSetting('whatsapp_business_number', '');
        $this->enableAutoRelease = (bool) $this->getSetting('enable_auto_release', true);
        $this->enableAutoExpiry = (bool) $this->getSetting('enable_auto_expiry', true);
        $this->enableWhatsAppLogs = (bool) $this->getSetting('enable_whatsapp_logs', true);
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

    public function saveSystemSettings()
    {
        $this->validate([
            'otpExpiryMinutes' => 'required|integer|min:1|max:60',
            'stockReleaseInterval' => 'required|integer|min:1|max:60',
            'stockReleaseQuantity' => 'required|integer|min:1|max:100',
            'stockExpiryMinutes' => 'required|integer|min:1|max:60',
            'whatsappBusinessNumber' => 'nullable|string|max:20',
            'enableAutoRelease' => 'boolean',
            'enableAutoExpiry' => 'boolean',
            'enableWhatsAppLogs' => 'boolean',
        ]);

        $this->setSetting('otp_expiry_minutes', $this->otpExpiryMinutes);
        $this->setSetting('stock_release_interval', $this->stockReleaseInterval);
        $this->setSetting('stock_release_quantity', $this->stockReleaseQuantity);
        $this->setSetting('stock_expiry_minutes', $this->stockExpiryMinutes);
        $this->setSetting('whatsapp_business_number', $this->whatsappBusinessNumber);
        $this->setSetting('enable_auto_release', $this->enableAutoRelease);
        $this->setSetting('enable_auto_expiry', $this->enableAutoExpiry);
        $this->setSetting('enable_whatsapp_logs', $this->enableWhatsAppLogs);

        session()->flash('success', 'System settings saved successfully!');
    }

    public function resetToDefaults()
    {
        $this->businessName = 'Cracker Store';
        $this->businessEmail = 'info@crackerstore.com';
        $this->businessPhone = '+91 9876543210';
        $this->businessAddress = 'Your Business Address';
        $this->upiId = 'your-upi@paytm';
        $this->otpExpiryMinutes = 5;
        $this->stockReleaseInterval = 10;
        $this->stockReleaseQuantity = 10;
        $this->stockExpiryMinutes = 10;
        $this->whatsappBusinessNumber = '';
        $this->enableAutoRelease = true;
        $this->enableAutoExpiry = true;
        $this->enableWhatsAppLogs = true;

        session()->flash('success', 'Settings reset to defaults!');
    }

    public function clearCache()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        
        session()->flash('success', 'Cache cleared successfully!');
    }

    public function backupDatabase()
    {
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('backups/' . $filename);
        
        // Create backups directory if it doesn't exist
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        // Database backup command
        $command = sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $path
        );

        exec($command);

        if (file_exists($path)) {
            return response()->download($path, $filename)->deleteFileAfterSend();
        }

        session()->flash('error', 'Database backup failed.');
    }

    public function render()
    {
        return view('livewire.admin.settings', [
            'totalUsers' => \App\Models\User::count(),
            'totalOrders' => \App\Models\Order::count(),
            'totalStocks' => \App\Models\Stock::count(),
            'totalPayments' => \App\Models\Payment::count(),
            'systemInfo' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'database' => config('database.default'),
                'storage_path' => storage_path(),
                'app_url' => config('app.url'),
            ]
        ]);
    }
} 