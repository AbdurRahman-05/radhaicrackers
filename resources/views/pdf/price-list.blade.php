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
            size: A4 portrait;
            margin: 10mm;
        }

        body, th, td {
            font-family: 'Noto Sans Tamil', 'DejaVu Sans', Arial, sans-serif;
        }

        .header-box {
            border: 1px solid #000;
            border-bottom: none;
            margin-bottom: 0;
            background: #fff;
            padding: 10px;
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
            font-size: 10px;
            color: #333;
            line-height: 1.4;
            text-align: left;
        }

        .title-logo {
            text-align: center;
            width: 100%;
            margin: 10px 0;
        }

        .title-logo img {
            max-width: 100%;
            width: 250px;
            height: auto;
            display: inline-block;
        }

        .company-title {
            font-family: 'Noto Sans Tamil', 'DejaVu Sans', Arial, sans-serif;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 1px;
        }

        .company-info-box {
            width: 100%;
            font-size: 10px;
            padding: 8px 10px;
            border: 1px solid #000;
            border-top: none;
            box-sizing: border-box;
            text-align: center;
        }

        .info-block {
            display: inline-block;
            padding: 0px 10px;
            box-sizing: border-box;
        }

        .page-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 15px 0 20px 0;
            color: #1E093B;
        }

        /* Category Section Container */
        .category-section {
            page-break-inside: auto;
            margin-bottom: 20px;
        }

        /* Product Table Styling */
        .price-list-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border: 1px solid #000;
            page-break-inside: auto;
        }

        /* Bind table headers together so Category Title + Column Headers + 1st row move together */
        .price-list-table thead {
            display: table-header-group;
        }

        /* Prevent individual product row mid-row page splits */
        .price-list-table tr {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .price-list-table th, .price-list-table td {
            border: 1px solid #000;
            padding: 6px 6px;
            text-align: center;
            font-size: 11px;
            font-family: 'Noto Sans Tamil', 'DejaVu Sans', Arial, sans-serif;
            box-sizing: border-box;
        }

        .price-list-table th {
            background: #f3f4f6;
            color: #1E093B;
            font-weight: bold;
        }

        /* Blue Heading Cell - centered heading, full 100% column width */
        .category-title-cell {
            font-size: 14px;
            font-weight: bold;
            color: #1E093B;
            background: #eef2ff !important;
            padding: 8px 12px;
            text-align: center !important;
            border-bottom: 1.5px solid #000;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .price-list-table tr:nth-child(even) td {
            background: #f9fafb;
        }

        .product-name {
            text-align: center;
            font-weight: 500;
            font-size: 13px;
        }

        .product-description {
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .text-left {
            text-align: left !important;
        }
    </style>
</head>
<body>
<div class="wrapper" style="margin-top:10px;">
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

    <div class="page-title">Price List {{ date('Y') }}</div>

    @php
        $groupedStocks = $stocks->groupBy('category');
        $categories = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get();
        $Sno = 0;

        $colSpan = 3; // S.No + Product Details + Qty
        if ($showImages ?? true) $colSpan++;
        if ($showPrices ?? true) $colSpan += 2;
    @endphp
    
    @foreach($categories as $category)
        @if(isset($groupedStocks[$category->name]) && $groupedStocks[$category->name]->count() > 0)
            <div class="category-section">
                <table class="price-list-table">
                    <thead>
                        <tr>
                            <th colspan="{{ $colSpan }}" class="category-title-cell">
                                {{ trim($category->name) }}
                            </th>
                        </tr>
                        <tr>
                            <th style="width: 40px;">S.No</th>
                            @if($showImages ?? true)
                            <th style="width: 90px;">Image</th>
                            @endif
                            <th class="text-left">Product Details</th>
                            @if($showPrices ?? true)
                            <th style="width: 60px;">MRP ₹</th>
                            <th style="width: 80px;">Disc(70%) + Sp.Disc(15%)</th>
                            @endif
                            <th style="width: 45px;">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedStocks[$category->name]->sortBy('order_within_category') as $item)
                            @php $Sno++; @endphp
                            <tr class="product-row">
                                <td>{{ $Sno }}</td>
                                @if($showImages ?? true)
                                <td>
                                    @if($item->image)
                                        <img src="{{ public_path('storage/' . $item->image) }}" alt="{{ $item->item_name }}" style="width: 65px; height: 65px; object-fit: contain;">
                                    @else
                                        <div style="width: 65px; height: 65px; background: #f5f5f5; text-align: center; font-size: 20px; line-height: 65px; margin: 0 auto;">
                                            @switch($item->category)
                                                @case('BOMBS') 💣 @break
                                                @case('SINGLE FLASH') ⚡ @break
                                                @case('ROCKETS') 🚀 @break
                                                @case('SPARKLERS') ✨ @break
                                                @case('CHIT PUT') 🎆 @break
                                                @case('TWINKLING STAR') ⭐ @break
                                                @case('GIFT BOX') 🎁 @break
                                                @case('BIJILI CRACKERS') ⚡ @break
                                                @default 🎆
                                            @endswitch
                                        </div>
                                    @endif
                                </td>
                                @endif
                                <td>
                                    <div class="product-name">{!! nl2br(e($item->item_name)) !!}</div>
                                    @if($item->description)
                                        <div class="product-description">{!! nl2br(e($item->description)) !!}</div>
                                    @endif
                                </td>
                                @if($showPrices ?? true)
                                <td>{{ number_format($item->original_price, 2) }}</td>
                                <td>{{ number_format($item->price, 2) }}</td>
                                @endif
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endforeach

    <!-- Footer -->
    <table style="width:100%; border-collapse:collapse; margin-top:20px; border:1px solid #000; page-break-inside:avoid;">
        <tr>
            <td style="width:100%; border:1px solid #000; text-align:center; height:48px; vertical-align:bottom; font-size:13px; font-weight:bold;">
                <span style="text-decoration:underline;">For Radhe Crackers</span>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
