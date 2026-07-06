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
        body, th, td, .company-title {
            font-family: 'Noto Sans Tamil', 'DejaVu Sans', Arial, sans-serif !important;
        }


        
.header-box {
    /* border: 1px solid #000; */
    border-radius: 10px 10px 0 0;
    margin-bottom: 0;
    background: #fff;
    padding: 10px;

    /* Ensure container doesn't collapse due to floating children */
    overflow: hidden;
}

.logo-container {
    display: inline-block;
    vertical-align: top;
    margin-right: 20px;
}

.logo-container img {
    width: 100px;
    height: auto;
   
}

.address-container {
    display: inline-block;
    vertical-align: top;
    max-width: calc(100% - 140px); 
}

.company-address {
    display: inline-block;
    font-size: 10px !important;
    color: #333;
    line-height: 1.4;
    /* border: 1px solid #000; */
    text-align: center;
}

.title-logo {
    text-align: center; /* Ensures centering if image is inline */
    width: 100%;
    margin: 10px 0;
}

.title-logo img {
    max-width: 100%;    /* Responsive scaling */
    width: 250px;
    height: auto;
    display: inline-block; /* Or block */
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
            width: 350px;
            font-size: 12px;
            border-left: 1px solid #d1d5db;
            padding-left: 10px;
             margin-left: auto;
              page-break-inside: avoid;
            
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
        }
        .summary-table td.label { text-align: left; background: #f3f4f6; color: #222; font-weight: bold; }
        .summary-table td.value { text-align: left; }
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
        @page {
            size: auto;
            margin: 10mm;
        }



         .company-info-box {
            width: fit-content;
            font-size: 10px;
            display: inline-block;
            
            padding: 10px 10px;
            flex-wrap: wrap; 
        }

        .info-block {
            display: inline-block;
            padding: 0px 10px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    
<div class="wrapper" style="border:2px solid #000; border-radius:10px; padding:5; margin-top:18px;">
    
    
            <div class="header-box">
                <div class="logo-container">
                    <img src="{{ public_path('images/company_logo.png') }}" alt="Company Logo" />
                </div>
                <div class="address-container">
                    <div class="title-logo">
                        <img src="{{ public_path('images/logotitle.png') }}" alt="Company Address" />
                    </div>
                    <span class="company-address">
                          4/273-11/7, Virudhunagar Main Road, Amathur ,Virudhunagar District, Tamilnadu-626005
            
                    </span>
                </div>
            </div>

        <hr>

       <div class="company-info-box">
            <div class="info-block">
                <span><strong>Contact Numbers:</strong> +91 88070 60809, +91 97510 48974</span>
            </div>
            
        <div class="info-block">
            <span><strong>Email:</strong> radhecrackers@gmail.com</span>
                
            </div>
            <div class="info-block">
                <span><strong>Website:</strong> www.radhecrackers.com</span>
            </div>
        </div>
                
<hr>



    <!-- Center Column: Table -->
    <div class="table-box" style="border:2px solid #000; border-radius:10px; padding:0; margin-top:18px;">
        <table class="main-table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <!-- <th>Code</th> -->
                    <th>Product</th>
                    <th>Pkt/Pcs</th>
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
                        $productId = $item['product_id'] ?? $item['stock_id'] ?? null;
                        $catalogSno = $catalogSnoMap[$productId] ?? '-';
                        $originalPrice = $item['original_price'] ?? $item['rate'] ?? $item['price'] ?? 0;
                        $quantity = $item['quantity'] ?? 0;
                        $line = $originalPrice * $quantity;
                        $subtotal += $line;
                    @endphp
                    <tr>
                        <td>{{ $catalogSno }}</td>
                        <!-- <td>{{ $item['product_id'] ?? '-' }}</td> -->
                        <td>{!! isset($item['product_name']) ? str_replace('&quot;', '"', $item['product_name']) : '-' !!}</td>
                        <td>{{ $item['description'] ?? '-' }}</td>
                        <td>{{ number_format($originalPrice, 2) }}</td>
                        <td>{{ $quantity }}</td>
                        <td class="total">{{ number_format($line, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Right Column: Summary -->
<div style="page-break-inside: avoid;">
    <div class="summary-box">
        <div class="section-title">Summary</div>
            
        @php
            // Subtotal using original MRP (original_price or rate or price)
            $subtotal = 0;
            if (isset($order->items) && is_iterable($order->items)) {
                foreach ($order->items as $item) {
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
        
        <div style="margin-top: 15px; font-size: 11px; color: #444; text-align: left; padding: 6px 10px; border-left: 3px solid #1E093B; background-color: #f9fafb; font-style: italic; page-break-inside: avoid;">
            <strong>Note:</strong> Quotations should be valid for 15 days from the order date.
        </div>
    </div>
    <table style="width:100%; border-collapse:collapse; margin-top:18px; border:1px solid #000; page-break-inside: avoid;">
        <tr>
            <td style="width:33.33%; border:1px solid #000; text-align:center; height:48px; vertical-align:bottom; font-size:12px; font-weight:bold;">
                <span style="text-decoration:underline;">Prepared By</span>
            </td>
            <td style="width:33.33%; border:1px solid #000; text-align:center; height:48px; vertical-align:bottom; font-size:12px; font-weight:bold;">
                <span style="text-decoration:underline;">Checked By</span>
            </td>
            <td style="width:33.33%; border:1px solid #000; text-align:center; height:48px; vertical-align:bottom; font-size:12px; font-weight:bold;">
                <span style="text-decoration:underline;">For Radhe Crackers</span>
            </td>
        </tr>
    </table>
</div>
</div>    
</body>
</html>
