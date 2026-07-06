<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Services\PDFService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserOrderController extends Controller
{
    public function index(Request $request)
    {
        // If redirected from track order, filter by mobile
        if (session()->has('track_order_mobile')) {
            $mobile = session('track_order_mobile');
            session()->forget('track_order_mobile');
            $orders = Order::where('customer_mobile', $mobile)->orderByDesc('created_at')->get();
            // No stats for guest view
            return view('user.orders.index', [
                'orders' => $orders,
                'totalOrders' => $orders->count(),
                'pendingOrders' => $orders->where('status', 'pending')->count(),
                'confirmedOrders' => $orders->where('status', 'confirmed')->count(),
                'dispatchedOrders' => $orders->where('status', 'dispatched')->count(),
            ]);
        }

        $user = Auth::user();
        $query = Order::query()
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->phone) {
                    $q->orWhere('customer_mobile', $user->phone);
                }
            });

        // Filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhere('customer_name', 'like', "%$search%")
                  ->orWhere('customer_mobile', 'like', "%$search%")
                  ->orWhere('notes', 'like', "%$search%")
                  ;
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('payment')) {
            $query->where('payment_status', $request->input('payment'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $orders = $query->orderByDesc('created_at')->get();

        // Statistics
        $statsQuery = Order::query()
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->phone) {
                    $q->orWhere('customer_mobile', $user->phone);
                }
            });
        $totalOrders = (clone $statsQuery)->count();
        $pendingOrders = (clone $statsQuery)->where('status', 'pending')->count();
        $confirmedOrders = (clone $statsQuery)->where('status', 'confirmed')->count();
        $dispatchedOrders = (clone $statsQuery)->where('status', 'dispatched')->count();

        return view('user.orders.index', compact('orders', 'totalOrders', 'pendingOrders', 'confirmedOrders', 'dispatchedOrders'));
    }

    public function show($orderId)
    {
        $user = Auth::user();
        $order = Order::where('id', $orderId)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->phone) {
                    $q->orWhere('customer_mobile', $user->phone);
                }
            })
            ->with(['logs', 'payment'])
            ->firstOrFail();

        return view('user.orders.show', compact('order'));
    }

    public function downloadPdf($orderId)
    {
        $user = Auth::user();
        $order = Order::where('id', $orderId)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->phone) {
                    $q->orWhere('customer_mobile', $user->phone);
                }
            })
            ->with(['user'])
            ->firstOrFail();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.user-order-invoice', compact('order'))->setPaper('a4', 'portrait');
        return $pdf->download('order_'.$order->id.'.pdf');
    }

    public function downloadInvoicePdf($orderId)
    {
        $user = Auth::user();
        $order = Order::where('id', $orderId)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->phone) {
                    $q->orWhere('customer_mobile', $user->phone);
                }
            })
            ->with(['user', 'payment', 'logs'])
            ->firstOrFail();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.user-order-invoice', compact('order'))->setPaper('a4', 'landscape');
        return $pdf->download('order-invoice-'.$order->id.'.pdf');
    }

    public function downloadAllInvoicePdf()
    {
        $user = Auth::user();
        $order = Order::where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->phone) {
                    $q->orWhere('customer_mobile', $user->phone);
                }
            })
            ->with(['user', 'payment', 'logs'])
            ->orderByDesc('created_at')
            ->first();
        if (!$order) {
            abort(404, 'No orders found');
        }
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.user-order-invoice', compact('order'))->setPaper('a4', 'landscape');
        return $pdf->download('all-orders-invoice.pdf');
    }

    public function exportCsv(Request $request)
    {
        $user = Auth::user();
        $orders = \App\Models\Order::where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->phone) {
                    $q->orWhere('customer_mobile', $user->phone);
                }
            })
            ->orderByDesc('created_at')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="my_orders.csv"',
        ];

        $columns = [
            'Order ID', 'Customer', 'Amount', 'Receive Amount', 'Status', 'Payment', 'Notes', 'Date',
            'Name', 'Mobile', 'Email', 'State', 'District', 'City', 'Delivery Point', 'Pin Code', 'Coupon', 'Verify Code',
            'Product', 'Content', 'Rate', 'Quantity', 'Item Total'
        ];

        $callback = function() use ($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($orders as $order) {
                if ($order->items && count($order->items) > 0) {
                    foreach ($order->items as $item) {
                        fputcsv($file, [
                            $order->id,
                            $order->user->name ?? $order->customer_name,
                            $order->total_amount ?? $order->total,
                            $order->receive_amount ?? '',
                            $order->status,
                            $order->payment_status ?? $order->payment->status ?? '',
                            $order->notes,
                            $order->created_at,
                            $order->customer_name,
                            $order->customer_mobile,
                            $order->customer_email,
                            $order->customer_state,
                            $order->customer_district,
                            $order->customer_city,
                            $order->delivery_point,
                            $order->pin_code,
                            $order->coupon_code,
                            $order->verify_code,
                            $item['product_name'] ?? '',
                            $item['content'] ?? '',
                            $item['rate'] ?? 0,
                            $item['quantity'] ?? 0,
                            $item['total'] ?? 0,
                        ]);
                    }
                } else {
                    fputcsv($file, [
                        $order->id,
                        $order->user->name ?? $order->customer_name,
                        $order->total_amount ?? $order->total,
                        $order->receive_amount ?? '',
                        $order->status,
                        $order->payment_status ?? $order->payment->status ?? '',
                        $order->notes,
                        $order->created_at,
                        $order->customer_name,
                        $order->customer_mobile,
                        $order->customer_email,
                        $order->customer_state,
                        $order->customer_district,
                        $order->customer_city,
                        $order->delivery_point,
                        $order->pin_code,
                        $order->coupon_code,
                        $order->verify_code,
                        '', '', '', '', '',
                    ]);
                }
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
} 