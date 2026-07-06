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
        $mobile = $request->input('tracking_number');
        $order = \App\Models\Order::where('customer_mobile', $mobile)->first();
        if ($order) {
            // Store the mobile in session to filter orders on /user/orders
            session(['track_order_mobile' => $mobile]);
            return redirect('/user/orders');
        } else {
            return view('pages.track-order', [
                'trackingNumber' => $mobile,
                'order' => null,
                'error' => 'No orders found for this mobile number.'
            ]);
        }
    }
} 