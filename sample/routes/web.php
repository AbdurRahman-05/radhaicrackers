<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\BestOffersController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\PriceListController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\ExpressShopController;

// Public routes
Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/payment-options', function () {
    return view('pages.payment-options');
})->name('payment-options');

Route::get('/track-order', function () {
    return view('pages.track-order');
})->name('track-order');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');

Route::get('/express-shop', [App\Http\Controllers\ExpressShopController::class, 'index'])->name('express-shop');


Route::post('/express-shop/estimate-pdf', [ExpressShopController::class, 'generateEstimatePdf'])->name('express-shop.estimate-pdf');


Route::get('/sale-products', [BestOffersController::class, 'index'])->name('sale-products');
Route::get('/sale-products/{category}', [BestOffersController::class, 'getByCategory'])->name('sale-products.category');

// Authentication routes
Route::get('/login', [OTPController::class, 'showLogin'])->name('login');
Route::post('/send-otp', [OTPController::class, 'sendOTP'])->name('send.otp');
Route::post('/verify-otp', [OTPController::class, 'verifyOTP'])->name('verify.otp');
Route::post('/logout', [OTPController::class, 'logout'])->name('logout');

// Protected user routes
Route::middleware('auth')->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

    Route::get('/order', [OrderController::class, 'showOrderForm'])->name('order.form');
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order/{id}', [OrderController::class, 'showOrder'])->name('order.show');
    Route::get('/order/{id}/pdf', [OrderController::class, 'downloadPDF'])->name('order.pdf');

    // Checkout routes
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'showForm'])->name('checkout.form');
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'submitForm'])->name('checkout.submit');

    Route::get('/price-list', [\App\Http\Controllers\PriceListController::class, 'show'])->name('price-list');
    Route::get('/price-list/download', [\App\Http\Controllers\PriceListController::class, 'download'])->middleware('auth')->name('price-list.download');
});

Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('orders', [UserOrderController::class, 'index'])->name('orders');
    Route::get('orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
    Route::get('orders/{order}/pdf', [UserOrderController::class, 'downloadPdf'])->name('orders.pdf');
    Route::get('orders-export-csv', [UserOrderController::class, 'exportCsv'])->name('orders.export.csv');
    // New invoice-style PDF routes
    Route::get('orders/{order}/invoice-pdf', [UserOrderController::class, 'downloadInvoicePdf'])->name('orders.invoice_pdf');
    Route::get('order-invoice-pdf', [UserOrderController::class, 'downloadAllInvoicePdf'])->name('orders.invoice_pdf_all');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Login (guest middleware applied)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'login']);
    });
    
    Route::post('/logout', [App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware('admin.auth')->group(function () {
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        
        // Orders Management
        Route::get('/orders', function () {
            return view('admin.orders.index');
        })->name('orders');
        Route::get('/orders/{id}', function ($id) {
            return view('admin.orders.details', compact('id'));
        })->name('orders.details');
        
        // Payments Management
        Route::get('/payments', function () {
            return view('admin.payments.index');
        })->name('payments');
        
        // Stock Management
        Route::get('/stocks', [App\Http\Controllers\Admin\StockController::class, 'index'])->name('stocks');
        Route::get('/stocks/add', [App\Http\Controllers\Admin\StockController::class, 'addForm'])->name('stocks.add');
        Route::post('/stocks/add', [App\Http\Controllers\Admin\StockController::class, 'store'])->name('stocks.store');
        Route::get('/stocks/{id}/edit', [App\Http\Controllers\Admin\StockController::class, 'edit'])->name('stocks.edit');
        Route::delete('/stocks/{id}', [App\Http\Controllers\Admin\StockController::class, 'destroy'])->name('stocks.destroy');
        Route::put('/stocks/{id}', [App\Http\Controllers\Admin\StockController::class, 'update'])->name('stocks.update');
        Route::post('/stocks/{id}/toggle-show-on-shop', [App\Http\Controllers\Admin\StockController::class, 'toggleShowOnShop'])->name('stocks.toggle-show-on-shop');
        Route::get('/stocks/logs', function () {
            return view('admin.stocks.logs');
        })->name('stocks.logs');
        Route::get('/stocks/download-template', [App\Http\Controllers\Admin\StockController::class, 'downloadTemplate'])->name('stocks.download-template');
        Route::post('/stocks/import-csv', [App\Http\Controllers\Admin\StockController::class, 'importCsv'])->name('stocks.import-csv');
        Route::post('/stocks/preview-import', [App\Http\Controllers\Admin\StockController::class, 'previewImport'])->name('stocks.preview-import');
        
        // Coupon Management
        Route::get('/coupons', [App\Http\Controllers\Admin\CouponController::class, 'index'])->name('coupons');
        Route::get('/coupons/create', [App\Http\Controllers\Admin\CouponController::class, 'create'])->name('coupons.create');
        Route::post('/coupons', [App\Http\Controllers\Admin\CouponController::class, 'store'])->name('coupons.store');
        Route::get('/coupons/{coupon}/edit', [App\Http\Controllers\Admin\CouponController::class, 'edit'])->name('coupons.edit');
        Route::put('/coupons/{coupon}', [App\Http\Controllers\Admin\CouponController::class, 'update'])->name('coupons.update');
        Route::delete('/coupons/{coupon}', [App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('coupons.destroy');
        Route::patch('/coupons/{coupon}/toggle-status', [App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
        Route::get('/coupons/{coupon}/usage', [App\Http\Controllers\Admin\CouponController::class, 'usage'])->name('coupons.usage');
        Route::get('/coupons/generate-code', [App\Http\Controllers\Admin\CouponController::class, 'generateCode'])->name('coupons.generate-code');
        Route::get('/coupons/demo', function () {
            return view('admin.coupons.demo');
        })->name('coupons.demo');
        
        // API Routes for Coupons (for frontend integration)
        Route::prefix('api')->name('api.')->group(function () {
            Route::post('/coupons/validate', [App\Http\Controllers\Api\CouponController::class, 'validate'])->name('coupons.validate');
            Route::get('/coupons/available', [App\Http\Controllers\Api\CouponController::class, 'available'])->name('coupons.available');
        });
        
        // User Management
        Route::get('/users', function () {
            return view('admin.users.index');
        })->name('users');
        Route::get('/users/{id}', function ($id) {
            return view('admin.users.details', compact('id'));
        })->name('users.details');
        
        // Content Management
        Route::get('/content', function () {
            return view('admin.content');
        })->name('content');
        
        // PDF Manager
        Route::get('/pdf-manager', function () {
            return view('admin.pdf-manager');
        })->name('pdf-manager');
        
        // WhatsApp Links
        Route::get('/whatsapp-links', function () {
            return view('admin.whatsapp-links');
        })->name('whatsapp-links');
        
        // Settings
        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('settings');
        
        // CSV Export
        Route::get('/export/orders', [App\Http\Controllers\AdminController::class, 'exportOrders'])->name('export.orders');
        Route::get('/export/users', [App\Http\Controllers\AdminController::class, 'exportUsers'])->name('export.users');
        Route::get('/export/payments', [App\Http\Controllers\AdminController::class, 'exportPayments'])->name('export.payments');
        Route::get('/export/stocks', [App\Http\Controllers\AdminController::class, 'exportStocks'])->name('export.stocks');

        // New route for viewing the order confirmation PDF inline in the browser
        Route::get('/orders/{order}/view-pdf', [\App\Http\Controllers\Admin\OrderController::class, 'viewPdf'])->name('orders.view_pdf');
        
        // New route for downloading the order confirmation PDF
        Route::get('/orders/{order}/download-pdf', [\App\Http\Controllers\Admin\OrderController::class, 'downloadPdf'])->name('orders.download_pdf');

        // Category Management
        Route::get('/categories', \App\Http\Livewire\Admin\Categories::class)->name('categories');
    });
});
