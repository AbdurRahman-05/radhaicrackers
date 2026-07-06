<!DOCTYPE html>
<html lang="ta">
<head>
    <meta charset="utf-8">
    <style>
        @page { size: A4 portrait; margin: 10mm; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #000; margin: 0; padding: 0; }
        .header-box {
            width: 100%;
            border: 1px solid #000;
            border-radius: 6px 6px 0 0;
            margin-bottom: 0;
            background: #fff;
        }
        .header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 18px 0 18px;
        }
        .logo {
            width: 90px;
            height: 60px;
            object-fit: contain;
        }
        .company-title {
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 1px;
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }
        .company-address {
            font-size: 13px;
            text-align: center;
            margin-top: 2px;
            margin-bottom: 2px;
        }
        .contact-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            padding: 0 18px 8px 18px;
            border-bottom: 1px solid #000;
        }
        .contact-item { display: flex; align-items: center; margin-right: 18px; }
        .contact-icon { font-size: 13px; margin-right: 4px; }
        .estimate-info {
            text-align: right;
            font-size: 13px;
            margin-top: 2px;
        }
        .wrapper { display: flex; flex-direction: row; width: 100%; }
        .info-box {
            width: 250px;
            padding: 16px 12px 12px 12px;
            font-size: 11px;
            background: #f8fafc;
            border-radius: 0 0 0 10px;
            border: 1px solid #e5e7eb;
            border-top: none;
            margin-right: 10px;
        }
        .info-box .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #1E093B;
            letter-spacing: 1px;
        }
        .info-box .label { font-weight: bold; color: #444; }
        .info-box .value { margin-bottom: 4px; }
        .table-box { flex: 1; overflow: hidden; }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px 10px 0 0;
            overflow: hidden;
            box-shadow: 0 2px 6px #0001;
            margin-bottom: 16px;
        }
        .main-table th, .main-table td {
            border: 1px solid #d1d5db;
            padding: 7px 6px;
            text-align: center;
            font-size: 11px;
        }
        .main-table th {
            background: #f3f4f6;
            color: #1E093B;
            font-weight: bold;
        }
        .main-table tr:nth-child(even) td { background: #f9fafb; }
        .main-table td.total { font-weight: bold; color: #1E093B; background: #f3f4f6; }
        .summary-box {
            width: 200px;
            font-size: 12px;
            border-left: 1px solid #d1d5db;
            padding-left: 10px;
            margin-left: 10px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .summary-table td {
            border: 1px solid #d1d5db;
            padding: 7px 8px;
            font-size: 12px;
            background: #f9fafb;
        }
        .summary-table tr:last-child td {
            font-size: 13px;
            font-weight: bold;
            color: #fff;
            background: #1E093B;
        }
        .summary-table td.label { text-align: left; background: #f3f4f6; color: #222; font-weight: bold; }
        .summary-table td.value { text-align: right; }
        .label-group {
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            height: 100%;
            margin-top: 15px;
        }
        .rotated-label {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            white-space: nowrap;
            border: 1px solid #333;
            text-align: center;
            padding: 20px 4px;
            font-weight: bold;
            background: #f3f4f6;
            color: #1E093B;
            border-radius: 6px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1E093B;
            margin-bottom: 6px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="header-box">
    <div class="header-row">
        <div>
            <!-- Logo: Uncomment and set src if you have a logo file -->
            <!-- <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Radhe Crackers Logo"> -->
        </div>
        <div style="flex:1;">
            <div class="company-title">ராதே கிராக்கர்ஸ்</div>
            <div class="company-address">3/180-5, Virudhunagar Main Road, G.N. Patti(Gurumoorthynayakkanpatti),<br>Amathur, Sivakasi, Virudhunagar (Dist), Tamil Nadu - 626005</div>
    </div>
        <div class="estimate-info">
        <div><strong>Estimate No:</strong> RAD{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
        <div><strong>Date:</strong> {{ $order->created_at->format('d/m/Y') }}</div>
        </div>
    </div>
    <div class="contact-row">
        <div class="contact-item"><span class="contact-icon">📞</span>+91 8807060809, 9751048974</div>
        <div class="contact-item"><span class="contact-icon">✉️</span> radhecrackers@gmail.com</div>
        <div class="contact-item"><span class="contact-icon">🌐</span> www.radhecrackers.com</div>
    </div>
</div>
<div class="wrapper">
    <!-- Left Column: Info -->
    <div class="info-box">
        <div class="section-title">Customer Details</div>
        <div class="value"><span class="label">Name:</span> {{ $order->customer_name }}</div>
        <div class="value"><span class="label">Mobile:</span> {{ $order->customer_mobile }}</div>
        <div class="value"><span class="label">Email:</span> {{ $order->customer_email }}</div>
        <div class="value"><span class="label">Address:</span> {{ $order->customer_city }}, {{ $order->customer_state }}</div>
        <div class="value"><span class="label">Pin Code:</span> {{ $order->pin_code }}</div>
    </div>
    <!-- Center Column: Table -->
    <div class="table-box">
        <div class="section-title">Order Details</div>
        <table class="main-table">
        <thead>
            <tr>
                <th>S.No</th>
                    <th>Code</th>
                <th>Product</th>
                <th>Content</th>
                <th>Rate ₹</th>
                <th>Qty</th>
                <th>Total ₹</th>
            </tr>
        </thead>
        <tbody>
                @php $subtotal = 0; @endphp
                @foreach($order->items_json as $index => $item)
                    @php $line = $item['total'] ?? $item['subtotal'] ?? 0; $subtotal += $line; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['product_id'] ?? '-' }}</td>
                        <td>{{ $item['product_name'] ?? '-' }}</td>
                        <td>{{ $item['content'] ?? '-' }}</td>
                        <td>{{ number_format($item['rate'] ?? $item['price'] ?? 0, 2) }}</td>
                        <td>{{ $item['quantity'] ?? '-' }}</td>
                        <td class="total">{{ number_format($line, 2) }}</td>
            </tr>
                @endforeach
        </tbody>
    </table>
    </div>
    <!-- Right Column: Summary -->
    <div class="summary-box">
        <div class="section-title">Summary</div>
        @php
            $discount70 = round($subtotal * 0.70, 2);
            $afterDiscount = $subtotal - $discount70;
            $specialDiscount = round($afterDiscount * 0.15, 2);
            $afterSpecial = $afterDiscount - $specialDiscount;
            $packing = round($afterSpecial * 0.05, 2);
            $netAmount = $afterSpecial + $packing;
    @endphp
        <table class="summary-table">
            <tr><td class="label">Sub Total</td><td class="value">₹{{ number_format($subtotal, 2) }}</td></tr>
            <tr><td class="label">Discount (70%)</td><td class="value">-₹{{ number_format($discount70, 2) }}</td></tr>
            <tr><td class="label">After Discount</td><td class="value">₹{{ number_format($afterDiscount, 2) }}</td></tr>
            <tr><td class="label">Special Disc (15%)</td><td class="value">-₹{{ number_format($specialDiscount, 2) }}</td></tr>
            <tr><td class="label">After Spl. Disc</td><td class="value">₹{{ number_format($afterSpecial, 2) }}</td></tr>
            <tr><td class="label">Packing (5%)</td><td class="value">₹{{ number_format($packing, 2) }}</td></tr>
            @if($order->coupon_code)
                <tr><td class="label">Coupon Code</td><td class="value">{{ $order->coupon_code }}</td></tr>
            @endif
            @if($order->coupon_discount)
                <tr><td class="label">Coupon Discount</td><td class="value">-₹{{ number_format($order->coupon_discount, 2) }}</td></tr>
            @endif
            <tr><td class="label">Net Amount</td><td class="value" style="background:#1E093B;color:#fff;font-size:14px;"><strong>₹{{ number_format(($netAmount - ($order->coupon_discount ?? 0)), 2) }}</strong></td></tr>
    </table>
        <div class="label-group">
            <div class="rotated-label">For Radhe Crackers</div>
            <div class="rotated-label">Checked By</div>
            <div class="rotated-label">Prepared By</div>
        </div>
    </div>
    </div>
</body>
</html> 
