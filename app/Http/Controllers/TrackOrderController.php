<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class TrackOrderController extends Controller
{
    public function show()
    {
        return view('pages.track-order');
    }

    public function track(Request $request)
    {
        $input = trim($request->input('tracking_number'));

        if (empty($input)) {
            return view('pages.track-order', [
                'error' => 'Please enter your Order ID or Mobile Number to track.'
            ]);
        }

        // Clean mobile number format if user searched phone number
        $cleanPhone = preg_replace('/[^0-9]/', '', $input);

        $orders = Order::with('items')
            ->where('id', $input)
            ->orWhere('customer_mobile', $input)
            ->orWhere('customer_mobile', $cleanPhone)
            ->orderBy('id', 'desc')
            ->get();

        if ($orders->count() > 0) {
            return view('pages.track-order', [
                'trackingNumber' => $input,
                'orders' => $orders,
                'order' => $orders->first(),
            ]);
        } else {
            return view('pages.track-order', [
                'trackingNumber' => $input,
                'orders' => collect(),
                'order' => null,
                'error' => 'No order found for ID / Mobile: ' . $input
            ]);
        }
    }
}