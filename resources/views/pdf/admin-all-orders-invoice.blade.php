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
        @page {
            size: auto;
            margin: 10mm;
        }


        body, th, td {
            font-family: 'Noto Sans Tamil', 'DejaVu Sans', Arial, sans-serif;
             
        }
.header-box {
    /* border: 1px solid #000; */
    border-radius:10px 10px 0 0;
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
    font-size: 9px;
    color: #333;
    line-height: 1.2;
    /* border: 1px solid #000; */
    text-align: left;
}




.title-logo {
    text-align: center; /* Ensures centering if image is inline */
    width: 100%;
    margin: 8px 0;
}

.title-logo img {
    max-width: 100%;    /* Responsive scaling */
    width: 250px;
    height: auto;
    display: inline-block; /* Or block */
}







        .company-title {
            font-family: 'Noto Sans Tamil', 'DejaVu Sans', Arial, sans-serif;
            font-size: 30px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 1px;
        }
        .company-address {
            font-size: 12px;
            text-align: center;
            margin-top: 2px;
            
        }
        
        
        .customer-box .label { font-weight: bold; color: #444; }
        .customer-box .value { margin-bottom: 4px; }
        .order-summary-flex {
         
            margin-top: 15px;
        }
        .order-table {

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
            padding: 7px 7px;
            text-align: center;
            font-size: 10px;
            font-family: 'Noto Sans Tamil', 'DejaVu Sans', Arial, sans-serif;
        }
        .order-table th.sno, .order-table td.sno { width: 40px; }
        .order-table th.code, .order-table td.code { width: 60px; }
        .order-table th.product, .order-table td.product { width: 220px; }
        .order-table th.mrp, .order-table td.mrp { width: 70px; }
        .order-table th.qty, .order-table td.qty { width: 50px; }
        .order-table th.total, .order-table td.total { width: 80px; }
        .order-table th {
            background: #f3f4f6;
            color: #1E093B;
            font-weight: bold;
            font-size: 10px;
        }
        .order-table tr:nth-child(even) td { background: #f9fafb; }
        .order-table td.total { font-weight: bold; color: #1E093B; background: #f3f4f6; }
        .summary-box {
           
             width: 350px;
            font-size: 12px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 0px 15px;
            background: #f8fafc;
            display: flex;
            align-items: end;
            justify-content: end;
            margin-left: auto;
            margin-top: 10px;           

        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            border: 1px solid #000;
        }
        .summary-table td {
            border: 1px solid #000;
            padding: 3px 10px;
            font-size: 10px;
            background: #f9fafb;
        }
        .summary-table tr:last-child td {
            font-size: 11px;
            font-weight: bold;
            color: #fff;
            background: #1E093B;
            
        }
        .summary-table td.label { text-align: left; background: #f3f4f6; color: #222; font-weight: bold; }
        .summary-table td.value { text-align: left; }

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

      .main-section {
    /* margin-top: 15px; */
    text-align: center; /* Center the box */
}

.customer-box {
    display: inline-block;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 0px 10px;
    font-size: 13px;
    width: auto;
    max-width: 700px;
    text-align: left;
}

.customer-box .title {
    font-size: 12px;
    font-weight: bold;
    color: #1E093B;
    margin-bottom: 8px;
    text-align: center;
}

.customer-table {
    width: 100%;
    border-collapse: collapse;
    padding: 10px;
}

.customer-table td {
    padding: 2px 10px;
    vertical-align: top;
    color: #333;
     font-size: 10px;
}
    </style>
</head>
<body>

<!-- Removed stray CSS rule -->
<div class="wrapper"
    style="border:2px solid #000; border-radius:10px; padding:0; margin-top:10px;">

        <div class="header-box">
            <div class="logo-container">
                <img src="{{ public_path('images/company_logo.png') }}" alt="Company Logo" />
            </div>
             <div class="address-container">
                 <div class="title-logo">
                        <img src="{{ public_path('images/logotitle.png') }}" alt="Company Address" />
                    </div>
                <span class="company-address">
                    134/1, Melaratha Street, Jeevan Milk Booth, Thiruthangal, Virudhunagar, Tamil Nadu, 626130
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
    

<div class="main-section">
    <div class="customer-box">
        <div class="label title">Customer Details</div>
        <table class="customer-table">
            <tr>
                <td><strong>Name:</strong></td>
                <td>{{ $order->customer_name }}</td>
                <td><strong>Mobile:</strong></td>
                <td>{{ $order->customer_mobile }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td>{{ $order->customer_email }}</td>
                <td><strong>State:</strong></td>
                <td>{{ $order->customer_state }}</td>
            </tr>
            <tr>
                <td><strong>District:</strong></td>
                <td>{{ $order->customer_district }}</td>
                <td><strong>City:</strong></td>
                <td>{{ $order->customer_city }}</td>
            </tr>
            <tr>
                <td><strong>Delivery Point:</strong></td>
                <td>{{ $order->delivery_point }}</td>
                <td><strong>Pin Code:</strong></td>
                <td>{{ $order->pin_code }}</td>
            </tr>
            <tr>
                <td><strong>Order Date:</strong></td>
                <td>{{ $order->created_at->format('d-m-Y') }}</td>
                <td><strong>Order ID:</strong></td>
                <td>{{ $order->id }}</td>
            </tr>
            <tr>
                <td><strong>Estimate Number:</strong></td>
                <td>{{ 'RAD' . $order->created_at->format('y') . str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td><strong>Estimate Date:</strong></td>
                <td>{{ \Carbon\Carbon::now()->format('d-m-y') }}</td>
            </tr>
            @if($order->payment_status === 'paid')
            <tr>
                @php
                    $paymentDate = '-';
                    if ($order->payment && $order->payment->verified_at) {
                        $paymentDate = $order->payment->verified_at->format('d-m-Y');
                    } elseif ($order->payment && $order->payment->created_at) {
                        $paymentDate = $order->payment->created_at->format('d-m-Y');
                    } else {
                        $paymentDate = $order->updated_at->format('d-m-Y');
                    }
                @endphp
                <td><strong>Payment Date:</strong></td>
                <td>{{ $paymentDate }}</td>
                <td><strong>Payment Status:</strong></td>
                <td>Paid</td>
            </tr>
            @endif
        </table>
    </div>
</div><br>





    @php
        $itemsArray = is_array($order->items) ? $order->items : (is_object($order->items) && method_exists($order->items, 'toArray') ? $order->items->toArray() : (array)$order->items);
        
        // Fetch all stocks metadata for sorting in one database query
        $sortedStocks = \App\Models\Stock::join('categories', function($join) {
                $join->on('stocks.category', '=', 'categories.name')
                     ->orOn('stocks.category_id', '=', 'categories.id');
            })
            ->select('stocks.id as stock_id', 'categories.sort_order as cat_sort', 'stocks.order_within_category as prod_sort', 'stocks.item_name as stock_name')
            ->get()
            ->keyBy('stock_id');

        // Sort itemsArray based on category sort_order and product order_within_category
        usort($itemsArray, function($a, $b) use ($sortedStocks) {
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
        
        $productCount = is_countable($itemsArray) ? count($itemsArray) : 0;
        $pages = [];
        if ($productCount <= 10) {
            // Case 1: All items on one page
            $pages[] = [
                'items' => $itemsArray,
                'pad' => 0,
                'show_summary' => true,
                'show_signature' => true,
            ];
        } elseif ($productCount <= 35) {
            // Case 2: 15 on page 1, rest (up to 19) on page 2
            $first = array_slice($itemsArray, 0, 19);
            $second = array_slice($itemsArray, 19);
            $pad1 = max(0, 19 - count($first));
            $pad2 = max(0, 19 - count($second));
            $pages[] = [
                'items' => $first,
                'pad' => $pad1,
                'show_summary' => false,
                'show_signature' => false,
            ];
            $pages[] = [
                'items' => $second,
                'pad' => $pad2,
                'show_summary' => true,
                'show_signature' => true,
            ];
        } else {
            // Case 3: 10 on page 1, then 30 per page, last page up to 20 (pad if <15)
            $first = array_slice($itemsArray, 0, 10);
            $rest = array_slice($itemsArray, 10);
            $pages[] = [
                'items' => $first,
                'pad' => max(0, 10 - count($first)),
                'show_summary' => false,
                'show_signature' => false,
            ];
            $chunks = array_chunk($rest, 30);
            foreach ($chunks as $i => $chunk) {
                // Last chunk: if <= 20, pad to 15 and show summary/signature
                if ($i === count($chunks) - 1 && count($chunk) <= 19) {
                    $pages[] = [
                        'items' => $chunk,
                        'pad' => max(0, 19 - count($chunk)),
                        'show_summary' => true,
                        'show_signature' => true,
                    ];
                } else {
                    $pages[] = [
                        'items' => $chunk,
                        'pad' => 0,
                        'show_summary' => false,
                        'show_signature' => false,
                    ];
                }
            }
        }
        $rowNumber = 1;
    @endphp


    <table class="order-table">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Product</th>
                <th>MRP ₹</th>
                <th>Qty</th>
                <th>Total ₹</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotal = 0; @endphp
            @foreach($pages as $page)
                @foreach($page['items'] as $item)
                    @php
                        $productId = is_array($item) ? ($item['product_id'] ?? $item['stock_id'] ?? null) : ($item->product_id ?? $item->stock_id ?? null);
                        $catalogSno = $catalogSnoMap[$productId] ?? '-';
                        $originalPrice = is_array($item) ? ($item['original_price'] ?? $item['rate'] ?? $item['price'] ?? 0) : ($item->original_price ?? $item->rate ?? $item->price ?? 0);
                        $quantity =is_array($item) ? ($item['quantity'] ?? 0) : ($item->quantity ?? 0);
                        $line = $originalPrice * $quantity;
                        $subtotal += $line;
                    @endphp
                    <tr>
                        <td class="sno">{{ $catalogSno }}</td>
                        <td class="product">{!! html_entity_decode(is_array($item) ? ($item['product_name'] ?? '-') : ($item->product_name ?? '-')) !!}</td>
                        <td class="mrp">{{ number_format($originalPrice, 2) }}</td>
                        <td class="qty">{{ $quantity }}</td>
                        <td class="total">{{ number_format($line, 2) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    @php $lastPage = end($pages); @endphp
    @php
        // Always show summary and signature on last page, even if >39 items
        $showSummarySignature = true;
        $itemCount = 0;
        if (isset($order->items) && is_iterable($order->items)) {
            foreach ($order->items as $item) {
                $quantity = is_array($item) ? ($item['quantity'] ?? 0) : ($item->quantity ?? 0);
                $itemCount += $quantity;
            }
        }
    @endphp
    @if($showSummarySignature)
        <div style="page-break-inside: avoid;">
            <div class="summary-box">
                <div class="label" style="font-size:12px; font-weight:bold; color:#1E093B; margin-top:0px;">Summary</div>
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
                    $discount70 = $subtotal * 0.70;
                    $afterDiscount = $subtotal - $discount70;
                    $specialDiscount = $afterDiscount * 0.15;
                    $afterSpecial = $afterDiscount - $specialDiscount;
                    $packing = $afterSpecial * 0.05;
                    $netAmount = $afterSpecial + $packing;
                    $couponDiscount = $order->coupon_discount ?? 0;
                    $finalAmount = $netAmount - $couponDiscount;
                    $gstAmount = 0;
                    if ($order->has_gst) {
                        $gstAmount = $finalAmount * 0.18;
                        $finalAmount += $gstAmount;
                    }
                    $finalAmount = max(0, $finalAmount);
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
                    @if($order->has_gst && $gstAmount > 0)
                        <tr><td class="label">GST (18%)</td><td class="value">₹{{ number_format($gstAmount, 2) }}</td></tr>
                    @endif
                    <tr><td class="label">Recieved Amount</td>
                        <td class="value" ><strong>
                            
                              @if(isset($order->receive_amount) && is_numeric($order->receive_amount) && $order->receive_amount > 0)
                ₹{{ number_format($order->receive_amount, 2) }}
            @else
                -
            @endif
                            </strong>
                        </td>
                    </tr>
                    <tr><td class="label">Net Amount</td><td class="value" style="background:#1E093B;color:#fff;font-size:11px;"><strong>₹{{ number_format($finalAmount, 2) }}</strong></td></tr>
                </table>
            </div>
        </div>
        <div style="margin-top: 15px; margin-bottom: 10px; font-size: 11px; color: #444; text-align: left; padding: 6px 10px; border-left: 3px solid #1E093B; background-color: #f9fafb; font-style: italic; page-break-inside: avoid;">
            <strong>Note:</strong> Once the status is "Confirmed", it cannot be changed back to "Pending".
        </div>
        <table style="width:100%; border-collapse:collapse; margin-top:10px; border:1px solid #000;page-break-inside: avoid;">
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
        
        <div style="text-align: center; margin-top: 20px; font-size: 13px; font-weight: bold; color: #1E093B; page-break-inside: avoid;">
            🎆 Wishing You a Happy, Safe & Prosperous Diwali! 🎇
        </div>
    @endif

    
</div>
</body>
</html>


