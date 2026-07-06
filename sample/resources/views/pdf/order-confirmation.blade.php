<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation - Cracker Shop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #f97316;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #f97316;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .order-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .customer-info, .order-details {
            flex: 1;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .customer-info {
            margin-right: 15px;
        }
        .order-details h3, .customer-info h3 {
            margin: 0 0 15px 0;
            color: #f97316;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f97316;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-row {
            font-weight: bold;
            background-color: #fef3c7 !important;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }
        .payment-info {
            background-color: #f0f9ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .payment-info h3 {
            margin: 0 0 10px 0;
            color: #1e40af;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎆 Cracker Shop</h1>
        <p>Order Confirmation</p>
        <p>Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="order-info">
        <div class="customer-info">
            <h3>👤 Customer Information</h3>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Phone:</strong> {{ $user->phone }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            <p><strong>Order Status:</strong> 
                <span class="status-badge status-pending">{{ ucfirst($order->status) }}</span>
            </p>
        </div>
        
        <div class="order-details">
            <h3>📋 Order Details</h3>
            <p><strong>Order ID:</strong> #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
            <p><strong>Total Amount:</strong> ₹{{ number_format($order->total, 2) }}</p>
            <p><strong>Items:</strong> {{ $items->count() }} products</p>
            @if($order->notes)
                <p><strong>Notes:</strong> {{ $order->notes }}</p>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price (₹)</th>
                <th>Subtotal (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->price, 2) }}</td>
                    <td>₹{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                <td><strong>₹{{ number_format($order->total, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="payment-info">
        <h3>💰 Payment Information</h3>
        <p><strong>Payment Method:</strong> UPI Payment</p>
        <p><strong>UPI ID:</strong> crackershop@upi</p>
        <p><strong>Amount to Pay:</strong> ₹{{ number_format($order->total, 2) }}</p>
        <p><strong>Instructions:</strong> Please complete the payment and provide the UPI Transaction ID for verification.</p>
    </div>

    <div style="background-color: #fef3c7; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <h3 style="margin: 0 0 10px 0; color: #92400e;">📞 Contact & Support</h3>
        <p><strong>Phone:</strong> +91 98765 43210</p>
        <p><strong>WhatsApp:</strong> Available 24/7 for support</p>
        <p><strong>Email:</strong> info@crackershop.com</p>
        <p><strong>Delivery:</strong> 1-2 business days after payment confirmation</p>
    </div>

    <div style="background-color: #f0f9ff; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <h3 style="margin: 0 0 10px 0; color: #1e40af;">🛡️ Safety Reminder</h3>
        <p>• All products meet safety standards</p>
        <p>• Follow usage instructions carefully</p>
        <p>• Keep away from children</p>
        <p>• Use in open areas only</p>
    </div>

    <div class="footer">
        <p><strong>Cracker Shop</strong> - Your trusted source for quality fireworks and crackers</p>
        <p>© {{ date('Y') }} Cracker Shop. All rights reserved.</p>
        <p>Thank you for choosing Cracker Shop! 🎆</p>
    </div>
</body>
</html> 