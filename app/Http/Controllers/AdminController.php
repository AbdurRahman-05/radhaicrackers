<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderLog;
use App\Models\Stock;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Services\PDFService;
use App\Services\WhatsAppService;

class AdminController extends Controller
{
    protected $pdfService;
    protected $whatsappService;

    public function __construct(PDFService $pdfService, WhatsAppService $whatsappService)
    {
        $this->pdfService = $pdfService;
        $this->whatsappService = $whatsappService;
    }

    /**
     * Admin Dashboard
     */
    public function dashboard(Request $request)
    {
        $selectedYear = $request->input('year', date('Y'));

        $userQuery = User::query();
        $orderQuery = Order::query();
        $paymentQuery = Payment::query();
        $activityQuery = OrderLog::query();

        if ($selectedYear !== 'all') {
            $userQuery->whereYear('created_at', $selectedYear);
            $orderQuery->whereYear('created_at', $selectedYear);
            $paymentQuery->whereYear('created_at', $selectedYear);
            $activityQuery->whereYear('created_at', $selectedYear);
        }

        $stats = [
            'total_users' => $userQuery->count(),
            'total_orders' => (clone $orderQuery)->count(),
            'verified_payments' => (clone $orderQuery)->whereIn('status', ['confirmed', 'dispatched', 'completed'])->where('payment_status', 'paid')->sum('total'),
            'stock_items' => Stock::active()->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_payments' => Payment::whereDate('verified_at', today())->sum('amount'),
            'recent_activity' => $activityQuery->with('order')->latest()->take(5)->get(),
            'verified_payment_details' => $paymentQuery->whereNotNull('verified_at')->latest()->take(5)->get(['amount','upi_id','transaction_id','notes','created_at']),
            
            // Order status counts
            'pending_orders' => (clone $orderQuery)->where('status', 'pending')->count(),
            'confirmed_orders' => (clone $orderQuery)->where('status', 'confirmed')->count(),
            'dispatched_orders' => (clone $orderQuery)->where('status', 'dispatched')->count(),
            'completed_orders' => (clone $orderQuery)->where('status', 'completed')->count(),
        ];

        // Get unique years from orders
        $years = Order::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        if (!in_array(date('Y'), $years)) {
            array_unshift($years, (int)date('Y'));
        }

        return view('admin.dashboard', compact('stats', 'selectedYear', 'years'));
    }

    /**
     * Order Management
     */
    public function orders(Request $request)
    {
        $query = Order::with(['user', 'items', 'payment']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            if ($request->payment_status === 'paid') {
                $query->whereHas('payment', function($q) {
                    $q->whereNotNull('verified_at');
                });
            } else {
                $query->whereDoesntHave('payment', function($q) {
                    $q->whereNotNull('verified_at');
                });
            }
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Order Details
     */
    public function orderDetails($id)
    {
        $order = Order::with(['user', 'items', 'payment', 'logs'])->findOrFail($id);
        return view('admin.orders.details', compact('order'));
    }

    /**
     * Update Order Status
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $oldStatus = strtolower($order->status);
        $newStatus = strtolower($request->status);

        if (in_array($oldStatus, ['confirmed', 'dispatched', 'completed']) && $newStatus === 'pending') {
            return redirect()->back()->with('error', 'Once the status is Confirmed, it cannot be changed back to Pending.');
        }

        $order->update(['status' => $request->status]);

        // Log the status change
        OrderLog::create([
            'order_id' => $order->id,
            'status' => $newStatus,
            'changed_by' => 'admin',
            'notes' => "Status changed from {$oldStatus} to {$newStatus}"
        ]);

        // Send WhatsApp notification if status is dispatched or completed
        if (in_array($newStatus, ['dispatched', 'completed'])) {
            $this->whatsappService->sendOrderStatusUpdate($order);
        }

        return redirect()->back()->with('success', 'Order status updated successfully');
    }

    /**
     * Regenerate Order PDF
     */
    public function regenerateOrderPDF($id)
    {
        try {
            $order = Order::with(['user', 'items'])->findOrFail($id);
            
            \Log::info('Generating PDF for order: ' . $order->id);
            
            $response = $this->pdfService->downloadOrderConfirmation($order);
            
            \Log::info('PDF generated successfully for order: ' . $order->id);
            
            return $response;
        } catch (\Exception $e) {
            \Log::error('PDF generation failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Payment Management
     */
    public function payments(Request $request)
    {
        $query = Payment::with(['order.user']);

        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->whereNotNull('verified_at');
            } else {
                $query->whereNull('verified_at');
            }
        }

        $payments = $query->latest()->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Verify Payment
     */
    public function verifyPayment($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update([
            'verified_at' => now(),
            'verified_by' => Auth::id()
        ]);

        // Update order status if needed
        $payment->order->update(['payment_status' => 'paid']);

        return redirect()->back()->with('success', 'Payment verified successfully');
    }

    /**
     * Stock Management
     */
    public function stocks()
    {
        $stocks = Stock::latest()->paginate(15);
        return view('admin.stocks.index', compact('stocks'));
    }

    /**
     * Add Stock
     */
    public function addStock(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        Stock::create($request->all());

        return redirect()->back()->with('success', 'Stock item added successfully');
    }

    /**
     * Update Stock
     */
    public function updateStock(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);
        $stock->update($request->all());

        return redirect()->back()->with('success', 'Stock updated successfully');
    }

    /**
     * Delete Stock
     */
    public function deleteStock($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return redirect()->back()->with('success', 'Stock deleted successfully');
    }

    /**
     * Stock Logs
     */
    public function stockLogs()
    {
        $logs = Stock::withTrashed()->latest()->paginate(15);
        return view('admin.stocks.logs', compact('logs'));
    }

    /**
     * User Management
     */
    public function users()
    {
        $users = User::withCount('orders')->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * User Details
     */
    public function userDetails($id)
    {
        $user = User::with(['orders.items', 'payments'])->findOrFail($id);
        return view('admin.users.details', compact('user'));
    }

    /**
     * Block/Unblock User
     */
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'unblocked' : 'blocked';
        return redirect()->back()->with('success', "User {$status} successfully");
    }

    /**
     * PDF Manager
     */
    public function pdfManager()
    {
        $priceListExists = Storage::disk('public')->exists('pdfs/price-list.pdf');
        $orderPDFs = Storage::disk('public')->files('pdfs/orders');

        return view('admin.pdf-manager', compact('priceListExists', 'orderPDFs'));
    }

    /**
     * Upload Price List
     */
    public function uploadPriceList(Request $request)
    {
        $request->validate([
            'price_list' => 'required|file|mimes:pdf|max:10240'
        ]);

        $file = $request->file('price_list');
        $file->storeAs('public/pdfs', 'price-list.pdf');

        return redirect()->back()->with('success', 'Price list uploaded successfully');
    }

    /**
     * Download Order PDF
     */
    public function downloadOrderPDF($filename)
    {
        $path = "public/pdfs/orders/{$filename}";
        
        if (!Storage::exists($path)) {
            abort(404);
        }

        return Storage::download($path);
    }

    /**
     * WhatsApp Links
     */
    public function whatsappLinks()
    {
        return view('admin.whatsapp-links');
    }

    /**
     * Generate WhatsApp OTP Link
     */
    public function generateOTPLink(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'name' => 'required|string'
        ]);

        $otp = rand(100000, 999999);
        $message = "Hi {$request->name}, your OTP for Radhe Crackers login is: {$otp}. Valid for 5 minutes.";

        $whatsappLink = $this->whatsappService->generateOTPLink($request->phone, $message);

        return response()->json(['link' => $whatsappLink]);
    }

    /**
     * Generate Order Summary Link
     */
    public function generateOrderSummaryLink(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::with(['user', 'items'])->findOrFail($request->order_id);
        $message = $this->whatsappService->generateOrderSummaryMessage($order);

        $whatsappLink = $this->whatsappService->generateOrderLink($order->user->phone, $message);

        return response()->json(['link' => $whatsappLink]);
    }

    /**
     * Settings
     */
    public function settings()
    {
        $settings = [
            'business_name' => config('app.name'),
            'admin_email' => config('mail.from.address'),
            'upi_id' => config('app.upi_id', ''),
            'otp_expiry' => config('app.otp_expiry', 5),
            'stock_release_interval' => config('app.stock_release_interval', 10),
            'stock_release_quantity' => config('app.stock_release_quantity', 10),
            'stock_expiry_time' => config('app.stock_expiry_time', 10),
        ];

        return view('admin.settings', compact('settings'));
    }

    /**
     * Update Settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'admin_email' => 'required|email',
            'upi_id' => 'required|string|max:255',
            'otp_expiry' => 'required|integer|min:1|max:60',
            'stock_release_interval' => 'required|integer|min:1|max:60',
            'stock_release_quantity' => 'required|integer|min:1|max:100',
            'stock_expiry_time' => 'required|integer|min:1|max:60',
        ]);

        // Update config or database settings
        // This would typically be stored in database or config files

        return redirect()->back()->with('success', 'Settings updated successfully');
    }

    /**
     * Export Orders
     */
    public function exportOrders(Request $request)
    {
        // Check if CSV content is in session (from Livewire)
        if (session()->has('export_csv_content')) {
            $csvContent = session('export_csv_content');
            $filename = session('export_csv_filename');
            
            // Clear session data
            session()->forget(['export_csv_content', 'export_csv_filename']);
            
            return response($csvContent)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename={$filename}");
        }

        // Fallback: generate CSV directly
        $query = Order::with(['user', 'items']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        return $this->exportOrdersCSV($orders);
    }

    /**
     * Export Orders CSV
     */
    private function exportOrdersCSV($orders)
    {
        $filename = 'orders_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            // CSV Headers
            fputcsv($file, ['Order ID', 'Customer', 'Phone', 'Items', 'Total', 'Status', 'Date']);
            foreach ($orders as $order) {
                $items = collect($order->items)->pluck('product_name')->implode(', ');
                fputcsv($file, [
                    $order->id,
                    $order->user->name,
                    $order->user->phone,
                    $items,
                    $order->total_amount,
                    $order->status,
                    $order->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Users
     */
    public function exportUsers(Request $request)
    {
        // Check if CSV content is in session (from Livewire)
        if (session()->has('export_csv_content')) {
            $csvContent = session('export_csv_content');
            $filename = session('export_csv_filename');
            
            // Clear session data
            session()->forget(['export_csv_content', 'export_csv_filename']);
            
            return response($csvContent)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename={$filename}");
        }

        // Fallback: generate CSV directly
        $users = User::withCount('orders')->get();
        return $this->exportUsersCSV($users);
    }

    /**
     * Export Payments
     */
    public function exportPayments(Request $request)
    {
        // Check if CSV content is in session (from Livewire)
        if (session()->has('export_csv_content')) {
            $csvContent = session('export_csv_content');
            $filename = session('export_csv_filename');
            
            // Clear session data
            session()->forget(['export_csv_content', 'export_csv_filename']);
            
            return response($csvContent)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename={$filename}");
        }

        // Fallback: generate CSV directly
        $payments = Payment::with(['order.user'])->get();
        return $this->exportPaymentsCSV($payments);
    }

    /**
     * Export Stocks
     */
    public function exportStocks(Request $request)
    {
        // Check if CSV content is in session (from Livewire)
        if (session()->has('export_csv_content')) {
            $csvContent = session('export_csv_content');
            $filename = session('export_csv_filename');
            
            // Clear session data
            session()->forget(['export_csv_content', 'export_csv_filename']);
            
            return response($csvContent)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename={$filename}");
        }

        // Fallback: generate CSV directly
        $stocks = Stock::all();
        return $this->exportStocksCSV($stocks);
    }

    /**
     * Export Users CSV
     */
    private function exportUsersCSV($users)
    {
        $filename = 'users_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, ['User ID', 'Name', 'Phone', 'Email', 'Status', 'Total Orders', 'Created Date']);
            
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->phone,
                    $user->email ?? 'N/A',
                    $user->is_active ? 'Active' : 'Inactive',
                    $user->orders_count,
                    $user->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Payments CSV
     */
    private function exportPaymentsCSV($payments)
    {
        $filename = 'payments_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, ['Payment ID', 'Order ID', 'Customer', 'Amount', 'UPI ID', 'Transaction ID', 'Status', 'Created Date']);
            
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->order_id,
                    $payment->order->user->name ?? 'N/A',
                    $payment->amount,
                    $payment->upi_id,
                    $payment->transaction_id,
                    $payment->status,
                    $payment->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Stocks CSV
     */
    private function exportStocksCSV($stocks)
    {
        $filename = 'stocks_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        $callback = function() use ($stocks) {
            $file = fopen('php://output', 'w');
            // CSV Headers (match bulk upload template)
            fputcsv($file, [
                'item_name', 'category', 'description', 'quantity', 'price', 'original_price', 'discount_percentage', 'special_discount_percentage', 'is_active', 'show_on_shop', 'is_popular', 'is_latest', 'expires_at', 'ordered_count', 'last_released_at', 'next_release_at', 'youtube_url', 'image'
            ]);
            foreach ($stocks as $stock) {
                fputcsv($file, [
                    $stock->item_name,
                    $stock->category,
                    $stock->description,
                    $stock->quantity,
                    $stock->price,
                    $stock->original_price,
                    $stock->discount_percentage,
                    $stock->special_discount_percentage,
                    $stock->is_active ? '1' : '0',
                    $stock->show_on_shop ? '1' : '0',
                    $stock->is_popular ? '1' : '0',
                    $stock->is_latest ? '1' : '0',
                    $stock->expires_at ? $stock->expires_at->format('Y-m-d H:i:s') : '',
                    $stock->ordered_count,
                    $stock->last_released_at ? $stock->last_released_at->format('Y-m-d H:i:s') : '',
                    $stock->next_release_at ? $stock->next_release_at->format('Y-m-d H:i:s') : '',
                    $stock->youtube_url,
                    $stock->image
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}