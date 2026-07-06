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
        // Coupon components
        Livewire::component('admin.coupons.bulk-upload', \App\Http\Livewire\Admin\Coupons\BulkUpload::class);
        Livewire::component('admin.coupons.export-csv', \App\Http\Livewire\Admin\Coupons\ExportCsv::class);
        
        // Component components
        Livewire::component('components.best-offers', BestOffers::class);
        Livewire::component('components.shop-categories', ShopCategories::class);
    }
}
