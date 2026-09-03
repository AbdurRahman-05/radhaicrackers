<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        body, th, td, div, span, h1, h2, h3, p {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif !important;
        }

        body {
            color: #111827;
            font-size: 11px;
            line-height: 1.3;
        }

        .page-title {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin: 12px 0 14px 0;
            color: #1E093B;
            text-transform: uppercase;
        }

        /* Category Section Container */
        .category-section {
            page-break-inside: auto;
            margin-bottom: 15px;
        }

        /* Product Table Styling */
        .price-list-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border: 1.5px solid #1E093B;
            page-break-inside: auto;
        }

        /* Bind table headers together */
        .price-list-table thead {
            display: table-header-group;
        }

        /* Prevent individual product row mid-row page splits */
        .price-list-table tr {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .price-list-table th, .price-list-table td {
            border: 1px solid #9ca3af;
            padding: 5px 6px;
            font-size: 10.5px;
            box-sizing: border-box;
        }

        .price-list-table th {
            background: #f3f4f6;
            color: #1E093B;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        /* Category Title Cell */
        .category-title-cell {
            font-size: 13px;
            font-weight: bold;
            color: #1E093B;
            background: #eef2ff !important;
            padding: 7px 10px;
            text-align: center !important;
            border-bottom: 1.5px solid #1E093B;
            text-transform: uppercase;
        }

        .price-list-table tr:nth-child(even) td {
            background: #fafafa;
        }

        .product-name {
            font-weight: bold;
            font-size: 11px;
            color: #111827;
        }

        .product-description {
            font-size: 9.5px;
            color: #6b7280;
            margin-top: 2px;
        }

        .text-left {
            text-align: left !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .image-placeholder {
            width: 50px;
            height: 50px;
            background: #f3f4f6;
            border: 1px dashed #d1d5db;
            color: #9ca3af;
            font-size: 8px;
            text-align: center;
            line-height: 50px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <table style="width: 100%; border-collapse: collapse; border: 1.5px solid #1E093B; background: #fff; margin-bottom: 0;">
        <tr>
            <td style="width: 110px; padding: 8px 10px; vertical-align: middle; text-align: center; border: none; border-bottom: 1px solid #e5e7eb;">
                <img src="{{ public_path('images/company_logo.png') }}" alt="Company Logo" style="width: 95px; height: auto;" />
            </td>
            <td style="padding: 8px 15px 8px 0; vertical-align: middle; text-align: center; border: none; border-bottom: 1px solid #e5e7eb;">
                <div style="text-align: center; margin-bottom: 4px;">
                    <img src="{{ public_path('images/logotitle.png') }}" alt="Radhe Crackers" style="width: 250px; height: auto; display: inline-block;" />
                </div>
                <div style="font-size: 9.5px; color: #374151; line-height: 1.3; text-align: center;">
                    4/273-11/7, Virudhunagar Main Road, Amathur, Virudhunagar District, Tamilnadu - 626005
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="background: #fdfaf6; padding: 5px 10px; text-align: center; border: none;">
                <table style="width: 100%; border: none; border-collapse: collapse;">
                    <tr>
                        <td style="border: none; text-align: left; width: 38%; padding: 0; font-size: 9px; color: #1f2937;"><strong>Contact:</strong> +91 88070 60809, +91 97510 48974</td>
                        <td style="border: none; text-align: center; width: 34%; padding: 0; font-size: 9px; color: #1f2937;"><strong>Email:</strong> radhecrackers@gmail.com</td>
                        <td style="border: none; text-align: right; width: 28%; padding: 0; font-size: 9px; color: #1f2937;"><strong>Website:</strong> www.radhecrackers.com</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

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
                            <th style="width: 36px;" class="text-center">S.No</th>
                            @if($showImages ?? true)
                            <th style="width: 65px;" class="text-center">Image</th>
                            @endif
                            <th class="text-left" style="padding-left: 8px;">Product Details</th>
                            @if($showPrices ?? true)
                            <th style="width: 75px;" class="text-right" style="padding-right: 8px;">MRP (Rs.)</th>
                            <th style="width: 90px;" class="text-right" style="padding-right: 8px;">Rate (Rs.)</th>
                            @endif
                            <th style="width: 42px;" class="text-center">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedStocks[$category->name]->sortBy('order_within_category') as $item)
                            @php $Sno++; @endphp
                            <tr class="product-row">
                                <td class="text-center" style="vertical-align: middle;">{{ $Sno }}</td>
                                @if($showImages ?? true)
                                <td class="text-center" style="vertical-align: middle; padding: 4px;">
                                    @if($item->image && file_exists(public_path('storage/' . $item->image)))
                                        <img src="{{ public_path('storage/' . $item->image) }}" alt="{{ $item->item_name }}" style="width: 50px; height: 50px; object-fit: contain;">
                                    @else
                                        <div class="image-placeholder">No Image</div>
                                    @endif
                                </td>
                                @endif
                                <td class="text-left" style="vertical-align: middle; padding: 4px 8px;">
                                    <div class="product-name">{!! nl2br(e($item->item_name)) !!}</div>
                                    @if($item->description)
                                        <div class="product-description">{!! nl2br(e($item->description)) !!}</div>
                                    @endif
                                </td>
                                @if($showPrices ?? true)
                                <td class="text-right" style="vertical-align: middle; padding-right: 8px;">
                                    {{ number_format($item->original_price, 2) }}
                                </td>
                                <td class="text-right" style="vertical-align: middle; padding-right: 8px; font-weight: bold; color: #1E093B;">
                                    {{ number_format($item->price, 2) }}
                                </td>
                                @endif
                                <td class="text-center" style="vertical-align: middle;"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endforeach

    <!-- Footer -->
    <table style="width:100%; border-collapse:collapse; margin-top:15px; border:1.5px solid #1E093B; page-break-inside:avoid;">
        <tr>
            <td style="width:100%; border:none; text-align:center; height:45px; vertical-align:middle; font-size:12px; font-weight:bold; color:#1E093B;">
                <span style="text-decoration:underline;">For Radhe Crackers</span>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
