<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SetupAdminPortal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup admin portal with default settings and content';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up Admin Portal...');

        // Create settings table if it doesn't exist
        if (!DB::getSchemaBuilder()->hasTable('settings')) {
            $this->createSettingsTable();
        }

        // Insert default settings
        $this->insertDefaultSettings();

        // Create default admin user if not exists
        $this->createDefaultAdmin();

        // Create default content
        $this->createDefaultContent();

        $this->info('Admin Portal setup completed successfully!');
        $this->info('Default admin credentials: admin@radhecrackers.com / admin123');
    }

    private function createSettingsTable()
    {
        $this->info('Creating settings table...');
        
        DB::statement('
            CREATE TABLE settings (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `key` VARCHAR(255) NOT NULL UNIQUE,
                value TEXT,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL
            )
        ');
    }

    private function insertDefaultSettings()
    {
        $this->info('Inserting default settings...');

        $settings = [
            // Business Settings
            'business_name' => 'Radhe Crackers',
            'business_email' => 'info@radhecrackers.com',
            'business_phone' => '+91 9876543210',
            'business_address' => 'Your Business Address, City, State - PIN',
            'upi_id' => 'radhecrackers@paytm',

            // System Settings
            'otp_expiry_minutes' => '5',
            'stock_release_interval' => '10',
            'stock_release_quantity' => '10',
            'stock_expiry_minutes' => '10',
            'whatsapp_business_number' => '+91 9876543210',
            'enable_auto_release' => '1',
            'enable_auto_expiry' => '1',
            'enable_whatsapp_logs' => '1',

            // WhatsApp Templates
            'whatsapp_otp_template' => "🔐 *OTP Verification*\n\nHello {name},\n\nYour OTP is: *{otp}*\n\nPlease enter this code to verify your account.\n\nValid for 5 minutes.",
            'whatsapp_order_summary_template' => "🛒 *Order Summary*\n\nOrder ID: #{order_id}\nCustomer: {name}\nPhone: {phone}\n\n*Items:*\n{items}\n\nTotal: ₹{amount}\nStatus: {status}",
            'whatsapp_payment_reminder_template' => "💰 *Payment Reminder*\n\nDear {name},\n\nPayment for order #{order_id} is pending.\nAmount: ₹{amount}\n\nPlease complete payment to confirm your order.",
            'whatsapp_dispatch_template' => "🚚 *Order Dispatched*\n\nDear {name},\n\nYour order #{order_id} has been dispatched!\n\nWe'll keep you updated on delivery status.",
            'whatsapp_welcome_template' => "🎉 *Welcome to Radhe Crackers*\n\nHello {name},\n\nThank you for registering with us!\n\nWe have the best crackers for all occasions.",

            // Content Pages
            'about_content' => '<h2>About Radhe Crackers</h2><p>We are a leading provider of high-quality crackers and fireworks for all occasions. With years of experience, we ensure safety and satisfaction.</p>',
            'contact_content' => '<h2>Contact Us</h2><p>Phone: +91 9876543210<br>Email: info@radhecrackers.com<br>Address: Your Business Address</p>',
            'payment_options_content' => '<h2>Payment Options</h2><p>We accept UPI payments. Please provide your UPI ID and Transaction ID after payment.</p>',
            'privacy_policy_content' => '<h2>Privacy Policy</h2><p>Your privacy is important to us. We collect only necessary information for order processing.</p>',
            'terms_conditions_content' => '<h2>Terms & Conditions</h2><p>By using our service, you agree to our terms and conditions.</p>',
        ];

        foreach ($settings as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }
    }

    private function createDefaultAdmin()
    {
        $this->info('Creating default admin user...');

        $admin = User::where('email', 'admin@radhecrackers.com')->first();
        
        if (!$admin) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@radhecrackers.com',
                'phone' => '+91 9876543210',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }
    }

    private function createDefaultContent()
    {
        $this->info('Creating default content...');

        // Create sample stocks if none exist
        if (DB::table('stocks')->count() == 0) {
            $stocks = [
                ['name' => 'Gold Lakshmi', 'description' => 'Premium Lakshmi crackers', 'quantity' => 50, 'price' => 150.00],
                ['name' => 'Twinkling Star', 'description' => 'Beautiful twinkling effect', 'quantity' => 40, 'price' => 120.00],
                ['name' => 'Bijili Crackers', 'description' => 'Traditional bijili crackers', 'quantity' => 60, 'price' => 80.00],
                ['name' => 'Rocket Pack', 'description' => 'High-flying rockets', 'quantity' => 30, 'price' => 200.00],
                ['name' => 'Sparklers', 'description' => 'Safe sparklers for kids', 'quantity' => 100, 'price' => 50.00],
            ];

            foreach ($stocks as $stock) {
                DB::table('stocks')->insert([
                    'name' => $stock['name'],
                    'description' => $stock['description'],
                    'quantity' => $stock['quantity'],
                    'price' => $stock['price'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 