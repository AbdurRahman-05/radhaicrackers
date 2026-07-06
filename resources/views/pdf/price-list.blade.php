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
            font-size: 10px;
            color: #333;
            line-height: 1.4;
            /* border: 1px solid #000; */
            text-align: left;
        }

        .title-logo {
            text-align: center;
            width: 100%;
            margin: 10px 0;
        }

        .title-logo img {
            max-width: 100%;    /* Responsive scaling */
            width: 250px;
            height: auto;
            display: inline-block; /* Or block */
        }

        .company-title {
            font-family: 'Noto Sans Tamil', 'DejaVu Sans', Arial, sans-serif;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 1px;
        }

        .main-section {
            margin-top: 18px;
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

        .page-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            color: #1E093B;
        }

        .price-list-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            /* border-radius: 10px; */
            overflow: hidden;
            margin-bottom: 20px;
            border: 1px solid #000;
        }

        .price-list-table th, .price-list-table td {
            border: 1px solid #000;
            padding: 8px 7px;
            text-align: center;
            font-size: 11px;
            font-family: 'Noto Sans Tamil', 'DejaVu Sans', Arial, sans-serif;
        }

        .price-list-table th {
            background: #f3f4f6;
            color: #1E093B;
            font-weight: bold;
        }

        .price-list-table tr:nth-child(even) td {
            background: #f9fafb;
        }

        .category-title {
            font-size: 15px;
            font-weight: bold;
            margin: 15px 0;
            color: #1E093B;
            background: #f3f4f6;
            padding: 6px;
            border-radius: 5px;
        }

        .product-name {
            text-align: center;
            font-weight: 500;
             font-size: 14px;
        }

        .product-description {
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .product-image {
            max-width: 70px;
            max-height: 70px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 1px;
            background: white;
        }

        .product-image-placeholder {
            width: 70px;
            height: 70px;
            background: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
    </style>
</head>
<body>
<div class="wrapper" style="border:2px solid #000; border-radius:10px; padding:0; margin-top:18px;">
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

    <div class="page-title">Price List 2025</div>

    @php
    $groupedStocks = $stocks->groupBy('category');
    $categories = \App\Models\Category::orderBy('sort_order')->get();
    @endphp
    

    @php
        $Sno = 0;
    @endphp
    
    @foreach($categories as $category)
        @if(isset($groupedStocks[$category->name]))
        <div class="category-title">{{ $category->name }}</div>
            <table class="price-list-table">
            <thead>
                <tr>
                    <th style="width: 40px;">S.No</th>
                    @if($showImages ?? true)
                    <th style="width: 100px;">Image</th>
                    @endif
                    <th>Product Details</th>
                    @if($showPrices ?? true)
                    <th style="width: 50px;">MRP ₹</th>
                    <th style="width: 60px;">Disc(70%) + Sp.Disc(15%)</th>
                    @endif
                    <th style="width: 50px;">Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupedStocks[$category->name]->sortBy('order_within_category') as $item)
                    
                    @php
                        $Sno++; 
                    @endphp
                
                    <tr>
                        <td>{{ $Sno }}</td>
                        @if($showImages ?? true)
                        <td>
                            @if($item->image)
                                <img src="{{ public_path('storage/' . $item->image) }}" alt="{{ $item->item_name }}" style="width: 80px; height: 80px; object-fit: contain;">
                            @else
                                <div style="width: 80px; height: 80px; background: #f5f5f5;  text-align: center;font-size: 24px;">
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
                        <td>
                        {{ number_format($item->original_price, 2) }}</td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        @endif
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach

    <!-- Footer -->
    <table style="width:100%; border-collapse:collapse; margin-top:32px; border:1px solid #000;">
        <tr>
            <td style="width:100%; border:1px solid #000; text-align:center; height:48px; vertical-align:bottom; font-size:13px; font-weight:bold;">
                <span style="text-decoration:underline;">For Radhe Crackers</span>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
