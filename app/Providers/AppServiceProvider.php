<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Http\Livewire\Auth\LoginForm;
use App\Http\Livewire\Pages\OrderNow;
use App\Http\Livewire\Pages\Home;
use App\Http\Livewire\Pages\Cart;
use App\Http\Livewire\Pages\Search;
use App\Http\Livewire\User\Dashboard as UserDashboard;
use App\Http\Livewire\Admin\Dashboard as AdminDashboard;
use App\Http\Livewire\Components\BestOffers;
use App\Http\Livewire\Components\ShopCategories;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-run schema migration logic for GST and Transport fields
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('orders')) {
                \Illuminate\Support\Facades\Schema::table('orders', function ($table) {
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'has_gst')) {
                        $table->boolean('has_gst')->default(false);
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'gst_amount')) {
                        $table->decimal('gst_amount', 10, 2)->default(0.00);
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'transport_provider')) {
                        $table->string('transport_provider')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'transport_details')) {
                        $table->string('transport_details')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'delivery_type')) {
                        $table->string('delivery_type')->nullable();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'paid_at')) {
                        $table->timestamp('paid_at')->nullable();
                    }
                });
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('gst_bills')) {
                \Illuminate\Support\Facades\Schema::create('gst_bills', function ($table) {
                    $table->id();
                    $table->string('bill_number')->unique();
                    $table->unsignedBigInteger('order_id')->nullable();
                    $table->string('customer_name');
                    $table->text('customer_address')->nullable();
                    $table->string('customer_gstin')->nullable();
                    $table->date('bill_date');
                    $table->string('hsn_code')->default('3604');
                    $table->string('transport')->nullable();
                    $table->string('no_of_cases')->nullable();
                    $table->string('place_of_supply')->nullable();
                    $table->decimal('subtotal', 12, 2)->default(0.00);
                    $table->decimal('cgst_rate', 5, 2)->default(9.00);
                    $table->decimal('cgst_amount', 12, 2)->default(0.00);
                    $table->decimal('sgst_rate', 5, 2)->default(9.00);
                    $table->decimal('sgst_amount', 12, 2)->default(0.00);
                    $table->decimal('igst_rate', 5, 2)->default(0.00);
                    $table->decimal('igst_amount', 12, 2)->default(0.00);
                    $table->decimal('round_off', 8, 2)->default(0.00);
                    $table->decimal('grand_total', 12, 2)->default(0.00);
                    $table->string('amount_in_words')->nullable();
                    $table->timestamps();
                });
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('gst_bill_items')) {
                \Illuminate\Support\Facades\Schema::create('gst_bill_items', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('gst_bill_id');
                    $table->unsignedBigInteger('stock_id')->nullable();
                    $table->string('particulars');
                    $table->integer('qty')->default(1);
                    $table->decimal('rate', 10, 2)->default(0.00);
                    $table->string('per')->default('1 Nos');
                    $table->decimal('amount', 12, 2)->default(0.00);
                    $table->timestamps();

                    $table->foreign('gst_bill_id')->references('id')->on('gst_bills')->onDelete('cascade');
                });
            }
        } catch (\Exception $e) {
            \Log::error('Schema migration failed for GST fields/tables: ' . $e->getMessage());
        }
        
        Livewire::component('stock-image-upload', \App\Http\Livewire\StockImageUpload::class);
        // Auth components
        Livewire::component('auth.login-form', LoginForm::class);
        
        // Page components
        Livewire::component('pages.order-now', OrderNow::class);
        Livewire::component('pages.home', Home::class);
        Livewire::component('pages.cart', Cart::class);
        Livewire::component('pages.search', Search::class);
        
        // User components
        Livewire::component('user.dashboard', UserDashboard::class);
        
        // Admin components
        Livewire::component('admin.dashboard', AdminDashboard::class);
        Livewire::component('admin.orders', \App\Http\Livewire\Admin\Orders::class);
        Livewire::component('admin.order-details', \App\Http\Livewire\Admin\OrderDetails::class);
        Livewire::component('admin.payments', \App\Http\Livewire\Admin\Payments::class);
        Livewire::component('admin.stocks', \App\Http\Livewire\Admin\Stocks::class);
        Livewire::component('admin.stock-logs', \App\Http\Livewire\Admin\StockLogs::class);
        Livewire::component('admin.users', \App\Http\Livewire\Admin\Users::class);
        Livewire::component('admin.content-pages', \App\Http\Livewire\Admin\ContentPages::class);
        Livewire::component('admin.pdf-manager', \App\Http\Livewire\Admin\PdfManager::class);
        Livewire::component('admin.whatsapp-links', \App\Http\Livewire\Admin\WhatsAppLinks::class);
        Livewire::component('admin.settings', \App\Http\Livewire\Admin\Settings::class);
        Livewire::component('admin.user-details', \App\Http\Livewire\Admin\UserDetails::class);
        Livewire::component('admin.categories', \App\Http\Livewire\Admin\Categories::class);
        Livewire::component('admin.coupons.bulk-upload', \App\Http\Livewire\Admin\Coupons\BulkUpload::class);
        Livewire::component('admin.coupons.export-csv', \App\Http\Livewire\Admin\Coupons\ExportCsv::class);
        Livewire::component('admin.home-contant', \App\Http\Livewire\Admin\HomeContant::class);
        Livewire::component('admin.stock-ordering', \App\Livewire\Admin\StockOrdering::class);
        
        // Component components
        Livewire::component('components.best-offers', BestOffers::class);
        Livewire::component('components.shop-categories', ShopCategories::class);
    }
}
