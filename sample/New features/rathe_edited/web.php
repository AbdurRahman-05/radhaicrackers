<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\BestOffersController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\PriceListController;

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

    Route::get('/user/orders', function () {
        return view('user.orders');
    })->name('user.orders');

    Route::get('/order', [OrderController::class, 'showOrderForm'])->name('order.form');
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order/{id}', [OrderController::class, 'showOrder'])->name('order.show');
    Route::get('/order/{id}/pdf', [OrderController::class, 'downloadPDF'])->name('order.pdf');

    Route::get('/price-list', [\App\Http\Controllers\PriceListController::class, 'show'])->name('price-list');
    Route::get('/price-list/download', [\App\Http\Controllers\PriceListController::class, 'download'])->middleware('auth')->name('price-list.download');
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


        // bulk import stock data starts here
        //newly added
        Route::post('/stocks/import-csv', [App\Http\Controllers\Admin\StockController::class, 'importCsv'])->name('stocks.import-csv');
        Route::post('/stocks/preview-import', [App\Http\Controllers\Admin\StockController::class, 'previewImport'])->name('stocks.preview-import');
        Route::get('/stocks/download-template', [App\Http\Controllers\Admin\StockController::class, 'downloadTemplate'])->name('stocks.download-template');
        Route::get('/stocks/logs', function () {
            return view('admin.stocks.logs');
        })->name('stocks.logs');
        //newly added
        //bulk import stock data ends here
        



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
    });
});
