<!DOCTYPE html>
<html lang="ta">
<head>
    <meta charset="utf-8">
    <style>
        @font-face {
            font-family: 'Noto Sans Tamil';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path('fonts/NotoSansTamil-Regular.ttf') }}') format('truetype');
        }
        @page { size: A4 portrait; margin: 10mm; }
        body, th, td {
            font-family: 'Noto Sans Tamil', 'DejaVu Sans', Arial, sans-serif;
        }
        .header-box {
            width: 100%;
            border: 1px solid #000;
            border-radius: 10px 10px 0 0;
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
            width: 120px;
            height: 80px;
            object-fit: contain;
        }
        .company-title {
            font-family: 'Noto Sans Tamil', 'DejaVu Sans', Arial, sans-serif;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 1px;
        }
        .company-address {
            font-size: 13px;
            text-align: center;
            margin-top: 2px;
            margin-bottom: 2px;
        }
        .estimate-info {
            text-align: right;
            font-size: 13px;
            margin-top: 2px;
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
        .main-section {
            display: flex;
            flex-direction: row;
            gap: 18px;
            margin-top: 18px;
        }
        .customer-box {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px 18px 14px 18px;
            margin-bottom: 0;
            font-size: 12px;
            min-width: 260px;
            max-width: 340px;
        }
        .customer-box .label { font-weight: bold; color: #444; }
        .customer-box .value { margin-bottom: 4px; }
        .order-summary-flex {
            display: flex;
            flex-direction: row;
            gap: 18px;
            align-items: flex-start;
            margin-top: 18px;
        }
        .order-table {
            flex: 2;
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 0;
            border: 1px solid #000;
        }
        .order-table th, .order-table td {
            border: 1px solid #000;
            padding: 8px 7px;
            text-align: center;
            font-size: 11px;
            font-family: 'Noto Sans Tamil', 'DejaVu Sans', Arial, sans-serif;
        }
        .order-table th {
            background: #f3f4f6;
            color: #1E093B;
            font-weight: bold;
        }
        .order-table tr:nth-child(even) td { background: #f9fafb; }
        .order-table td.total { font-weight: bold; color: #1E093B; background: #f3f4f6; }
        .summary-box {
            flex: 1;
            min-width: 220px;
            max-width: 260px;
            font-size: 12px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 14px 12px;
            background: #f8fafc;
            margin-left: auto;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 1px solid #000;
        }
        .summary-table td {
            border: 1px solid #000;
            padding: 7px 8px;
            font-size: 12px;
            background: #f9fafb;
        }
        .summary-table tr:last-child td {
            font-size: 13px;
            font-weight: bold;
            color: #fff;
            background: #1E093B;
            border-radius: 6px;
        }
        .summary-table td.label { text-align: left; background: #f3f4f6; color: #222; font-weight: bold; }
        .summary-table td.value { text-align: right; }
    </style>
</head>
<body>

<!-- Add a wrapper with a strong border around the order-summary-flex section -->
<div style="border:2px solid #000; border-radius:10px; padding:0; margin-top:18px;">
    <div class="header-box">
        <img src="{{ public_path('images/head.png') }}" style="width:100%;max-width:100%;height:auto;display:block;margin:0 auto;" alt="Header" />
    </div>
    <div class="main-section">
        <div class="customer-box">
            <div class="label" style="font-size:13px; font-weight:bold; color:#1E093B; margin-bottom:6px;">Customer Details</div>
            <div class="value"><span class="label">Name:</span> {{ $order->customer_name }}</div>
            <div class="value"><span class="label">Mobile:</span> {{ $order->customer_mobile }}</div>
            <div class="value"><span class="label">Email:</span> {{ $order->customer_email }}</div>
            <div class="value"><span class="label">State:</span> {{ $order->customer_state }}</div>
            <div class="value"><span class="label">District:</span> {{ $order->customer_district }}</div>
            <div class="value"><span class="label">City:</span> {{ $order->customer_city }}</div>
            <div class="value"><span class="label">Delivery Point:</span> {{ $order->delivery_point }}</div>
            <div class="value"><span class="label">Pin Code:</span> {{ $order->pin_code }}</div>
        </div>
    </div>
    <div class="order-summary-flex">
        <table class="order-table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Code</th>
                    <th>Product</th>
                    <th>MRP ₹</th>
                    <th>Qty</th>
                    <th>Total ₹</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $subtotal = 0;
                    
                    // Fetch all stocks metadata for sorting in one database query
                    $sortedStocks = \App\Models\Stock::join('categories', function($join) {
                            $join->on('stocks.category', '=', 'categories.name')
                                 ->orOn('stocks.category_id', '=', 'categories.id');
                        })
                        ->select('stocks.id as stock_id', 'categories.sort_order as cat_sort', 'stocks.order_within_category as prod_sort', 'stocks.item_name as stock_name')
                        ->get()
                        ->keyBy('stock_id');

                    // Convert to collection and sort
                    $sortedItems = collect($order->items)->sort(function($a, $b) use ($sortedStocks) {
                        $idA = is_array($a) ? ($a['product_id'] ?? $a['stock_id'] ?? null) : ($a->product_id ?? $a->stock_id ?? null);
                        $idB = is_array($b) ? ($b['product_id'] ?? $b['stock_id'] ?? null) : ($b->product_id ?? $b->stock_id ?? null);
                        
                        $metaA = $idA ? $sortedStocks->get($idA) : null;
                        $metaB = $idB ? $sortedStocks->get($idB) : null;
                        
                        $catSortA = $metaA ? $metaA->cat_sort : 99999;
                        $catSortB = $metaB ? $metaB->cat_sort : 99999;
                        
                        if ($catSortA !== $catSortB) {
                            return $catSortA <=> $catSortB;
                        }
                        
                        $prodSortA = $metaA ? $metaA->prod_sort : 99999;
                        $prodSortB = $metaB ? $metaB->prod_sort : 99999;
                        
                        if ($prodSortA !== $prodSortB) {
                            return $prodSortA <=> $prodSortB;
                        }
                        
                        $nameA = $metaA ? $metaA->stock_name : (is_array($a) ? ($a['product_name'] ?? '') : ($a->product_name ?? ''));
                        $nameB = $metaB ? $metaB->stock_name : (is_array($b) ? ($b['product_name'] ?? '') : ($b->product_name ?? ''));
                        return strcmp($nameA, $nameB);
                    });

                    // Pre-calculate full active stocks serial mapping to match price list catalog serials
                    $allActiveCats = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get();
                    $allActiveStocks = \App\Models\Stock::where('is_active', true)->get()->groupBy('category');
                    $catalogSnoMap = [];
                    $snoCounter = 0;
                    foreach ($allActiveCats as $cat) {
                        $catStocks = $allActiveStocks->get($cat->name) ?? $allActiveStocks->get($cat->id) ?? collect();
                        foreach ($catStocks->sortBy('order_within_category') as $stockItem) {
                            $snoCounter++;
                            $catalogSnoMap[$stockItem->id] = $snoCounter;
                        }
                    }
                @endphp
                @foreach($sortedItems as $item)
                    @php 
                        $productId = is_array($item) ? ($item['product_id'] ?? $item['stock_id'] ?? null) : ($item->product_id ?? $item->stock_id ?? null);
                        $catalogSno = $catalogSnoMap[$productId] ?? '-';
                        $line = is_array($item) ? ($item['total'] ?? $item['subtotal'] ?? 0) : ($item->total ?? $item->subtotal ?? 0); 
                        $subtotal += $line; 
                    @endphp
                    <tr>
                        <td>{{ $catalogSno }}</td>
                        <td>{{ is_array($item) ? ($item['product_id'] ?? '-') : ($item->product_id ?? '-') }}</td>
                        <td>{!! html_entity_decode(is_array($item) ? ($item['product_name'] ?? '-') : ($item->product_name ?? '-')) !!}</td>
                        <td>{{ number_format(is_array($item) ? ($item['rate'] ?? $item['price'] ?? 0) : ($item->rate ?? $item->price ?? 0), 2) }}</td>
                        <td>{{ is_array($item) ? ($item['quantity'] ?? '-') : ($item->quantity ?? '-') }}</td>
                        <td class="total">{{ number_format($line, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="summary-box">
            <div class="label" style="font-size:13px; font-weight:bold; color:#1E093B; margin-bottom:6px;">Summary</div>
            @php
                // Subtotal using original prices
                $subtotal = 0;
                if (isset($order->items) && is_iterable($order->items)) {
                    foreach ($order->items as $item) {
                        // Use original price if available, otherwise use current price
                        $originalPrice = $item['original_price'] ?? $item['rate'] ?? $item['price'] ?? 0;
                        $quantity = $item['quantity'] ?? 0;
                        $subtotal += $originalPrice * $quantity;
                    }
                }
                // Unified discount calculation
                $discount70 = round($subtotal * 0.70, 2);
                $afterDiscount = $subtotal - $discount70;
                $specialDiscount = round($afterDiscount * 0.15, 2);
                $afterSpecial = $afterDiscount - $specialDiscount;
                $packing = round($afterSpecial * 0.05, 2);
                $netAmount = $afterSpecial + $packing;
                $couponDiscount = $order->coupon_discount ?? 0;
                $finalAmount = $netAmount - $couponDiscount;
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
                @if($couponDiscount)
                    <tr><td class="label">Coupon Discount</td><td class="value">-₹{{ number_format($couponDiscount, 2) }}</td></tr>
                @endif
                <tr><td class="label">Net Amount</td><td class="value" style="background:#1E093B;color:#fff;font-size:14px;"><strong>₹{{ number_format($finalAmount, 2) }}</strong></td></tr>
            </table>
        </div>
    </div>

    <!-- Note -->
    <div style="margin-top: 15px; font-size: 11px; color: #444; text-align: left; padding: 6px 10px; border-left: 3px solid #1E093B; background-color: #f9fafb; font-style: italic; page-break-inside: avoid;">
        <strong>Note:</strong> Once the status is "Confirmed", it cannot be changed back to "Pending".
    </div>

    <!-- Signature Row -->
    <table style="width:100%; border-collapse:collapse; margin-top:32px; border:1px solid #000; page-break-inside: avoid;">
        <tr>
            <td style="width:33.33%; border:1px solid #000; text-align:center; height:48px; vertical-align:bottom; font-size:13px; font-weight:bold;">
                <span style="text-decoration:underline;">Prepared By</span>
            </td>
            <td style="width:33.33%; border:1px solid #000; text-align:center; height:48px; vertical-align:bottom; font-size:13px; font-weight:bold;">
                <span style="text-decoration:underline;">Checked By</span>
            </td>
            <td style="width:33.33%; border:1px solid #000; text-align:center; height:48px; vertical-align:bottom; font-size:13px; font-weight:bold;">
                <span style="text-decoration:underline;">For Radhe Crackers</span>
            </td>
        </tr>
    </table>
    
    <div style="text-align: center; margin-top: 20px; font-size: 13px; font-weight: bold; color: #1E093B; page-break-inside: avoid;">
        🎆 Wishing You a Happy, Safe & Prosperous Diwali! 🎇
    </div>
</div>
</body>
</html> 
