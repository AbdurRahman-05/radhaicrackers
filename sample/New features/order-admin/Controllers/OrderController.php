<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderLog;
use App\Models\Payment;
use App\Services\WhatsAppService;
use App\Services\PDFService;

class OrderController extends Controller
{
    public function showOrderForm()
    {
        return view('pages.order');
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'total' => $request->total,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        // Create order items
        foreach ($request->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Create order log
        OrderLog::create([
            'order_id' => $order->id,
            'status' => 'pending',
            'notes' => 'Order created',
        ]);

        // Send WhatsApp notification
        $whatsappService = app(WhatsAppService::class);
        $whatsappService->sendOrderConfirmation($order);

        // Generate PDF
        $pdfService = app(PDFService::class);
        $pdfService->generateOrderConfirmation($order);

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully!',
            'order_id' => $order->id,
        ]);
    }

    public function showOrder($id)
    {
        $order = Order::with(['items', 'payment', 'logs'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('user.order-details', compact('order'));
    }

    public function downloadPDF($id)
    {
        $order = Order::with(['items', 'user'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $pdfService = app(PDFService::class);
        return $pdfService->downloadOrderConfirmation($order);
    }

    public function addPayment(Request $request, $orderId)
    {
        $request->validate([
            'upi_id' => 'required|string',
            'transaction_id' => 'required|string',
        ]);

        $order = Order::where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'upi_id' => $request->upi_id,
                'transaction_id' => $request->transaction_id,
                'verified_at' => null,
            ]
        );

        return redirect()->back()->with('success', 'Payment details submitted successfully!');
    }
} 