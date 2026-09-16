<!DOCTYPE html>
<html lang="ta">
<head>
    <meta charset="utf-8">
    <style>
        @font-face {
            font-family: 'Noto Sans Tamil';
            font-style: normal;
            font-weight: normal;
            src: url('data:font/truetype;charset=utf-8;base64,{{ base64_encode(file_get_contents(public_path("fonts/NotoSansTamil-Regular.ttf"))) }}') format('truetype');
        }
        @font-face {
            font-family: 'Noto Sans Tamil';
            font-style: normal;
            font-weight: bold;
            src: url('data:font/truetype;charset=utf-8;base64,{{ base64_encode(file_get_contents(public_path("fonts/NotoSansTamil-Regular.ttf"))) }}') format('truetype');
        }
        body, th, td, .company-title {
            font-family: 'Noto Sans Tamil', 'DejaVu Sans', Arial, sans-serif !important;
        }
        .company-title {
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
        .bo{
            border: 2px solid black;
        }
        .contact-row {
            
            font-size: 12px;
            padding: 10px 18px 8px 18px;
            
            text-align: left;
            white-space: nowrap;
        }
        .contact-item {
            display: inline-block;
            width: auto;
            margin-right: 12px;
            padding: 2px 6px;
            vertical-align: top;
        }
        

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
            border: 1px solid #000;
        }
        .main-table th, .main-table td {
            border: 1px solid #000;
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
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        
        .summary-container {
            position: fixed;
            bottom: 50px;
            right: 50px;
            width: 300px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 1px solid #000;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
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
        
        /* Page break rules */
        .summary-box {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        .summary-table {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        .wrapper {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        /* Force page break before summary if needed */
        .page-break-before {
            page-break-before: always;
            break-before: page;
        }
    </style>
</head>
<body>
    
<div class="wrapper" style="border:2px solid #000; border-radius:10px; padding:5; margin-top:18px;">
    <div class="header-box">
        <img src="{{ public_path('images/head.png') }}" style="width:100%;max-width:100%;height:auto;display:block;margin:0 auto;" alt="Header" />
    </div>

    <!-- Center Column: Table -->
    <div class="table-box" style="border:2px solid #000; border-radius:10px; padding:0; margin-top:18px;">
        <table class="main-table">
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
                    $sortedItems = collect($order->items_json ?? [])->sort(function($a, $b) use ($sortedStocks) {
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
                        $productId = $item['product_id'] ?? $item['stock_id'] ?? null;
                        $catalogSno = $catalogSnoMap[$productId] ?? '-';
                        $isGift = !empty($item['is_lucky_spin_gift']) || !empty($item['is_free_gift']);
                        $isCombo = !empty($item['is_combo']) || ($productId >= 999000 && $productId <= 999999) || (str_contains(strtoupper($item['product_name'] ?? ''), 'COMBO'));
                        $originalPrice = $item['original_price'] ?? $item['rate'] ?? $item['price'] ?? 0;
                        $quantity = $item['quantity'] ?? 0;

                        if ($isCombo) {
                            $comboPrice = (float)($item['price'] ?? $item['rate'] ?? 0);
                            $pNameUpper = strtoupper($item['product_name'] ?? '');
                            if ($comboPrice <= 0 || ($comboPrice > 10000 && str_contains($pNameUpper, '3K'))) {
                                if (str_contains($pNameUpper, '3K')) $comboPrice = 3000;
                                elseif (str_contains($pNameUpper, '5K')) $comboPrice = 5000;
                                elseif (str_contains($pNameUpper, '8K')) $comboPrice = 8000;
                                elseif (str_contains($pNameUpper, '10K')) $comboPrice = 10000;
                            }
                            $displayPrice = $comboPrice;
                            $line = $comboPrice * $quantity;
                        } else {
                            $displayPrice = $originalPrice;
                            $line = $isGift ? 0 : ($originalPrice * $quantity);
                            if (!$isGift) {
                                $subtotal += $line;
                            }
                        }
                    @endphp
                    <tr>
                        <td>{{ $isCombo ? 'COMBO' : $catalogSno }}</td>
                        <td>{{ $item['product_id'] ?? '-' }}</td>
                        <td>
                            {{ $item['product_name'] ?? '-' }}
                            @if($isGift)
                                <span style="color: #d97706; font-weight: bold; font-size: 10px;"> [FREE GIFT]</span>
                            @elseif($isCombo)
                                <span style="color: #b67121; font-weight: bold; font-size: 10px;"> [COMBO]</span>
                            @endif
                        </td>
                        <td>{{ $isGift ? 'FREE' : number_format($displayPrice, 2) }}</td>
                        <td>{{ $quantity }}</td>
                        <td class="total">{{ $isGift ? 'FREE (0.00)' : number_format($line, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Force page break before summary -->
    <div style="page-break-before: always; break-before: page;"></div>
    
    <!-- Summary Page with bottom-right positioning -->
    <div style="height: 100vh; display: flex; align-items: flex-end; justify-content: flex-end; padding: 50px;">
        <div class="summary-box" style="border: 2px solid #000; border-radius: 10px; padding: 20px; background: #fff; width: 400px; max-width: 400px;">
            <div class="section-title" style="text-align: center; font-size: 18px; margin-bottom: 15px;">Summary</div>
            
        @php
            $regularSubtotal = 0;
            $comboSubtotal = 0;
            if (isset($order->items_json) && is_iterable($order->items_json)) {
                foreach ($order->items_json as $item) {
                    $isGift = !empty($item['is_lucky_spin_gift']) || !empty($item['is_free_gift']);
                    if ($isGift) continue;
                    $pId = (int)($item['product_id'] ?? 0);
                    $pName = (string)($item['product_name'] ?? '');
                    $isCombo = !empty($item['is_combo']) || ($pId >= 999000 && $pId <= 999999) || str_contains(strtoupper($pName), 'COMBO');
                    $quantity = $item['quantity'] ?? 0;
                    if ($isCombo) {
                        $comboPrice = (float)($item['price'] ?? $item['rate'] ?? 0);
                        $pNameUpper = strtoupper($pName);
                        if ($comboPrice <= 0 || ($comboPrice > 10000 && str_contains($pNameUpper, '3K'))) {
                            if (str_contains($pNameUpper, '3K')) $comboPrice = 3000;
                            elseif (str_contains($pNameUpper, '5K')) $comboPrice = 5000;
                            elseif (str_contains($pNameUpper, '8K')) $comboPrice = 8000;
                            elseif (str_contains($pNameUpper, '10K')) $comboPrice = 10000;
                        }
                        $comboSubtotal += $comboPrice * $quantity;
                    } else {
                        $originalPrice = $item['original_price'] ?? $item['rate'] ?? $item['price'] ?? 0;
                        $regularSubtotal += $originalPrice * $quantity;
                    }
                }
            }
            $discount70 = round($regularSubtotal * 0.70, 2);
            $afterDiscount = round($regularSubtotal - $discount70, 2);
            $specialDiscount = round($afterDiscount * 0.15, 2);
            $afterSpecial = round($afterDiscount - $specialDiscount, 2);
            
            $couponDiscount = (float)($order->coupon_discount ?? 0);
            $afterCoupon = max(0, round($afterSpecial - $couponDiscount, 2));
            $luckySpinDiscount = (float)($order->lucky_spin_discount ?? 0);
            $luckySpinPrize = $order->lucky_spin_prize ?? null;
            $netBeforeCombos = max(0, round($afterCoupon - $luckySpinDiscount, 2));

            // 9. Total Amount = (After Coupon / Spin) + Net Rate Items
            $totalAmount = round($netBeforeCombos + $comboSubtotal, 2);

            // 10. Add Package 5% = 5% on Total Amount
            $packing = isset($order->packing_charge_5_percent) && (float)$order->packing_charge_5_percent > 0 && abs((float)$order->packing_charge_5_percent - round($totalAmount * 0.05, 2)) < 5
                ? (float)$order->packing_charge_5_percent
                : (($totalAmount > 0) ? round($totalAmount * 0.05, 2) : 0);

            $finalAmount = max(0, round($totalAmount + $packing));
        @endphp

        <table class="summary-table" style="page-break-inside: avoid;">
            <tr><td class="label">Sub Total</td><td class="value">₹{{ number_format($regularSubtotal, 2) }}</td></tr>
            <tr><td class="label">Discount 70%</td><td class="value">-₹{{ number_format($discount70, 2) }}</td></tr>
            <tr><td class="label">After Discount</td><td class="value">₹{{ number_format($afterDiscount, 2) }}</td></tr>
            <tr><td class="label">Spl Discount 15%</td><td class="value">-₹{{ number_format($specialDiscount, 2) }}</td></tr>
            <tr><td class="label">After Spl Discount</td><td class="value">₹{{ number_format($afterSpecial, 2) }}</td></tr>
            @if($couponDiscount > 0 || !empty($order->coupon_code))
                <tr><td class="label">Coupon Discount @if(!empty($order->coupon_code))({{ $order->coupon_code }})@endif</td><td class="value">-₹{{ number_format($couponDiscount, 2) }}</td></tr>
                <tr><td class="label">After Coupon Discount</td><td class="value">₹{{ number_format($afterCoupon, 2) }}</td></tr>
            @endif
            @if(!empty($luckySpinPrize))
                @if($luckySpinDiscount > 0)
                    <tr><td class="label" style="color:#059669;font-weight:bold;">Lucky Spin Disc (5%) [{{ $luckySpinPrize }}]</td><td class="value" style="color:#059669;font-weight:bold;">-₹{{ number_format($luckySpinDiscount, 2) }}</td></tr>
                @else
                    <tr><td class="label" style="color:#B45309;font-weight:bold;">Lucky Wheel Prize</td><td class="value" style="color:#B45309;font-weight:bold;">{{ $luckySpinPrize }} (FREE)</td></tr>
                @endif
            @endif
            @if($comboSubtotal > 0)
                <tr><td class="label">Net Rate Items</td><td class="value">₹{{ number_format($comboSubtotal, 2) }}</td></tr>
            @endif
            <tr><td class="label">Total Amount</td><td class="value">₹{{ number_format($totalAmount, 2) }}</td></tr>
            <tr><td class="label">Add Package 5%</td><td class="value">₹{{ number_format($packing, 2) }}</td></tr>
            <tr><td class="label" style="background:#1E093B;color:#fff;font-size:12px;"><strong>Net Payable Amount</strong></td><td class="value" style="background:#1E093B;color:#fff;font-size:12px;"><strong>₹{{ number_format($finalAmount, 2) }}</strong></td></tr>
        </table>
        
        <div style="margin-top: 15px; font-size: 11px; color: #444; text-align: left; padding: 6px 10px; border-left: 3px solid #1E093B; background-color: #f9fafb; font-style: italic; page-break-inside: avoid;">
            <strong>Note:</strong> Quotations should be valid for 15 days from the order date.
        </div>
        </div>
    </div>
    
</body>
</html> 
