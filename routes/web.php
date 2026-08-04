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
use App\Http\Controllers\Auth\OTPLoginController;
use App\Http\Controllers\TrackOrderController;
use App\Livewire\Admin\HomeContentManager;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;







// Dynamic storage file server route (serves PDFs & images on Hostinger & local seamlessly)
Route::get('/storage/{path}', function ($path) {
    // 1. Check in public_path('storage/' . $path)
    $file1 = public_path('storage/' . $path);
    if (File::exists($file1) && !File::isDirectory($file1)) {
        $mime = File::mimeType($file1);
        return response()->file($file1, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    // 2. Check in storage_path('app/public/' . $path)
    $file2 = storage_path('app/public/' . $path);
    if (File::exists($file2) && !File::isDirectory($file2)) {
        $mime = File::mimeType($file2);
        return response()->file($file2, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    // 3. Check in public_path('images/' . $path)
    $file3 = public_path('images/' . $path);
    if (File::exists($file3) && !File::isDirectory($file3)) {
        $mime = File::mimeType($file3);
        return response()->file($file3, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    // 4. Default fallback image if requested file missing
    $defaultImage = public_path('images/firework-default.png');
    if (File::exists($defaultImage)) {
        return response()->file($defaultImage, ['Content-Type' => 'image/png']);
    }

    abort(404);
})->where('path', '.*');

// Public Unauthenticated PDF Direct Binary Stream Route for WhatsApp & Customers
Route::get('/public-pdf/{id}', function ($id) {
    $order = \App\Models\Order::with(['user', 'payment', 'logs'])->find($id);
    if (!$order) {
        abort(404);
    }
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.user-order-invoice', compact('order'))->setPaper('a4', 'portrait');
    return response($pdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="Radhe_Crackers_Order_#' . $id . '.pdf"'
    ]);
})->name('public.pdf_invoice');

















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

// Require authentication for these pages
Route::middleware('auth')->group(function () {
    Route::get('/estimate', [ShopController::class, 'index'])->name('shop');
    Route::get('/quotation', [App\Http\Controllers\ExpressShopController::class, 'index'])->name('express-shop');
    Route::post('/quotation/estimate-pdf', [App\Http\Controllers\ExpressShopController::class, 'estimatePdf'])->name('express-shop.estimate-pdf');
    Route::get('/price-list', [PriceListController::class, 'show'])->name('price-list');
    Route::get('/track-order', [TrackOrderController::class, 'show'])->name('track-order.show');
    Route::post('/track-order', [TrackOrderController::class, 'track'])->name('track-order.track');
});

// Remove or comment out the old public versions of these routes
// Route::get('/shop', [ShopController::class, 'index'])->name('shop');
// Route::get('/express-shop', [App\Http\Controllers\ExpressShopController::class, 'index'])->name('express-shop');
// Route::post('/express-shop/estimate-pdf', [App\Http\Controllers\ExpressShopController::class, 'estimatePdf'])->name('express-shop.estimate-pdf');
// Route::get('/price-list', [\App\Http\Controllers\PriceListController::class, 'show'])->name('price-list');
// Route::get('/track-order', [TrackOrderController::class, 'show'])->name('track-order.show');
// Route::post('/track-order', [TrackOrderController::class, 'track'])->name('track-order.track');

Route::get('/sale-products', [BestOffersController::class, 'index'])->name('sale-products');
Route::get('/sale-products/{category}', [BestOffersController::class, 'getByCategory'])->name('sale-products.category');

// Authentication routes
Route::get('/login', [OTPLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login/send-otp', [OTPLoginController::class, 'sendOtp'])->name('login.sendOtp');
Route::post('/login/verify-otp', [OTPLoginController::class, 'verifyOtp'])->name('login.verifyOtp');
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

// Smart Checkout routes
Route::get('/smart-checkout', [App\Http\Controllers\SmartCheckoutController::class, 'show'])->name('smart-checkout.show');
Route::post('/smart-checkout', [App\Http\Controllers\SmartCheckoutController::class, 'submit'])->name('smart-checkout.submit');
Route::post('/api/coupons/validate', [App\Http\Controllers\SmartCheckoutController::class, 'validateCoupon'])->name('smart-checkout.validate-coupon');
Route::get('/api/coupons/available', [App\Http\Controllers\SmartCheckoutController::class, 'getAvailableCoupons'])->name('smart-checkout.available-coupons');
Route::post('/smart-checkout/draft', [App\Http\Controllers\SmartCheckoutController::class, 'saveDraft'])->name('smart-checkout.save-draft');
Route::get('/smart-checkout/draft', [App\Http\Controllers\SmartCheckoutController::class, 'loadDraft'])->name('smart-checkout.load-draft');

    Route::get('/price-list', [PriceListController::class, 'show'])->name('price-list');
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

Route::post('/admin/stocks/{id}/toggle-active', [App\Http\Controllers\Admin\StockController::class, 'toggleStockStatus'])
    ->name('admin.stocks.toggle-active');
    
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
        // Export Orders (CSV, filter by status)
        Route::get('/orders/export', [App\Http\Controllers\AdminController::class, 'exportOrders'])->name('orders.export');
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

        // GST Bills Management
        Route::get('/gst-bills', [App\Http\Controllers\Admin\GstBillController::class, 'index'])->name('gst-bills.index');
        Route::get('/gst-bills/create', [App\Http\Controllers\Admin\GstBillController::class, 'create'])->name('gst-bills.create');
        Route::post('/gst-bills', [App\Http\Controllers\Admin\GstBillController::class, 'store'])->name('gst-bills.store');
        Route::get('/gst-bills/{id}/pdf', [App\Http\Controllers\Admin\GstBillController::class, 'showPdf'])->name('gst-bills.pdf');
        Route::delete('/gst-bills/{id}', [App\Http\Controllers\Admin\GstBillController::class, 'destroy'])->name('gst-bills.destroy');
        
        // Stock Management
        Route::get('/stocks', [App\Http\Controllers\Admin\StockController::class, 'index'])->name('stocks');
        Route::get('/stocks/add', [App\Http\Controllers\Admin\StockController::class, 'addForm'])->name('stocks.add');
        Route::post('/stocks/add', [App\Http\Controllers\Admin\StockController::class, 'store'])->name('stocks.store');
        Route::get('/stocks/{id}/edit', [App\Http\Controllers\Admin\StockController::class, 'edit'])->name('stocks.edit');
        Route::delete('/stocks/{id}', [App\Http\Controllers\Admin\StockController::class, 'destroy'])->name('stocks.destroy');
        Route::put('/stocks/{id}', [App\Http\Controllers\Admin\StockController::class, 'update'])->name('stocks.update');
        Route::get('/stocks/ordering', function () {
            return view('admin.stocks.ordering');
        })->name('stocks.ordering');
        Route::post('/stocks/{id}/toggle-show-on-shop', [App\Http\Controllers\Admin\StockController::class, 'toggleShowOnShop'])->name('stocks.toggle-show-on-shop');
        Route::post('/stocks/{id}/toggle-show-on-home', [App\Http\Controllers\Admin\StockController::class, 'toggleShowOnHome'])->name('stocks.toggle-show-on-home');
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
        Route::post('/coupons/import', [App\Http\Controllers\Admin\CouponController::class, 'importCsv'])->name('coupons.import');
        Route::get('/coupons/export-csv', [App\Http\Controllers\Admin\CouponController::class, 'exportCsv'])->name('coupons.export-csv');
        Route::get('/coupons/download-template', [App\Http\Controllers\Admin\CouponController::class, 'downloadTemplate'])->name('coupons.download-template');
        
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
        // Export Ordered Items CSV (product name and count only)
        Route::get('/export/ordered-items', [App\Http\Controllers\Admin\StockController::class, 'exportOrderedItems'])->name('export.ordered-items');
        
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
        
        // New route for downloading the order invoice PDF
        Route::get('/orders/{order}/download-invoice-pdf', [\App\Http\Controllers\Admin\OrderController::class, 'downloadInvoicePdf'])->name('orders.download_invoice_pdf');
        
        // New route for downloading all orders invoice PDF
        Route::get('/orders/download-all-invoice-pdf', [\App\Http\Controllers\Admin\OrderController::class, 'downloadAllInvoicePdf'])->name('orders.download_all_invoice_pdf');

        // Category Management
        Route::get('/categories', \App\Http\Livewire\Admin\Categories::class)->name('categories');
        // Home Page Products CRUD (no JS, no Livewire)
        Route::resource('homepage_products', App\Http\Controllers\Admin\HomepageProductController::class);
        Route::get('/admin/galleryImages-upload', \App\Livewire\Admin\StockImageUpload::class)->name('galleryImages-upload.index');
    });
});

    Route::get('/run-migrations', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            return 'Migration output: <pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
        } catch (\Exception $e) {
            return 'Error during migration: ' . $e->getMessage();
        }
    });

// Comprehensive fallback storage route for serving product images on Hostinger / shared hosting
Route::get('/storage/{path}', function ($path) {
    // 1. Clean path of duplicate prefixes
    $cleanFilename = preg_replace('#^(public/|storage/|stocks/|homepage_products/)+#', '', $path);
    $filename = basename($path);

    // List of candidate paths to locate the uploaded file
    $candidates = [
        storage_path('app/public/stocks/' . $cleanFilename),
        storage_path('app/public/stocks/' . $filename),
        storage_path('app/public/homepage_products/' . $cleanFilename),
        storage_path('app/public/homepage_products/' . $filename),
        storage_path('app/public/' . $path),
        storage_path('app/public/' . $cleanFilename),
        public_path('storage/stocks/' . $cleanFilename),
        public_path('storage/stocks/' . $filename),
        public_path('storage/homepage_products/' . $cleanFilename),
        public_path('storage/homepage_products/' . $filename),
        public_path('storage/' . $path),
        public_path('storage/' . $cleanFilename),
        public_path('uploads/' . $cleanFilename),
        public_path($path),
    ];

    foreach ($candidates as $filePath) {
        if (file_exists($filePath) && !is_dir($filePath)) {
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'svg' => 'image/svg+xml',
            ];
            $mime = $mimeTypes[$ext] ?? (function_exists('mime_content_type') ? (mime_content_type($filePath) ?: 'image/jpeg') : 'image/jpeg');
            return response()->file($filePath, ['Content-Type' => $mime]);
        }
    }

    abort(404);
})->where('path', '.*');

// Route fallback for direct stock image URLs
Route::get('/stocks/{path}', function ($path) {
    return redirect('/storage/stocks/' . $path);
})->where('path', '.*');


