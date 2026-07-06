<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Price List - Radhe Crackers</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #222;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 16px;
        }
        .header {
            margin-bottom: 24px;
        }
        .header h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .header p {
            color: #64748b;
            font-size: 1.2rem;
            text-align: center;
        }
        .price-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 32px;
        }
        .price-table th {
            background: #ff7300;
            color: #fff;
            padding: 16px 12px;
            text-align: left;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .price-table td {
            padding: 16px 12px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        .price-table tr:hover {
            background: #f9fafb;
        }
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #f3f4f6;
            display: block;
        }
        .product-icon {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            background: #f3f4f6;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .product-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 8px;
            word-break: break-word;
        }
        .product-description {
            font-size: 0.95rem;
            color: #64748b;
            margin-bottom: 8px;
            word-break: break-word;
            line-height: 1.4;
        }
        .product-category {
            font-size: 0.9rem;
            color: #6b7280;
            background: #f3f4f6;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
        }
        .stock-info {
            font-size: 0.9rem;
            color: #059669;
            margin-top: 8px;
        }
        .price-block {
            text-align: right;
        }
        .discount-badge {
            background: #ef4444;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 8px;
        }
        .original-price {
            color: #a1a1aa;
            text-decoration: line-through;
            font-size: 0.95rem;
            margin-bottom: 4px;
            display: block;
        }
        .sale-price {
            color: #f97316;
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #64748b;
            font-size: 1rem;
        }
        .image-column {
            width: 100px;
            text-align: center;
        }
        .details-column {
            width: 60%;
        }
        .price-column {
            width: 200px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Price List - Radhe Crackers</h1>
            <p>Quality fireworks at amazing prices</p>
        </div>
        
        <table class="price-table">
            <thead>
                <tr>
                    <th class="image-column">Image</th>
                    <th class="details-column">Product Details</th>
                    <th class="price-column">Price & Offers</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $product)
                    <tr>
                        <td class="image-column">
                            @if($product->image && file_exists(public_path('storage/' . $product->image)))
                                <img src="{{ public_path('storage/' . $product->image) }}" alt="{{ $product->item_name }}" class="product-image">
                            @else
                                <div class="product-icon">
                                    @switch($product->category)
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
                        <td class="details-column">
                            <div class="product-title">{!! nl2br(e($product->item_name)) !!}</div>
                            @if($product->description)
                                <div class="product-description">{!! nl2br(e($product->description)) !!}</div>
                            @endif
                            @if($product->category)
                                <div class="product-category">{{ $product->category }}</div>
                            @endif
                            <div class="stock-info">Available: {{ $product->quantity }} units</div>
                        </td>
                        <td class="price-column">
                            @if($product->discount_percentage)
                                <div class="discount-badge">-{{ $product->discount_percentage }}% OFF</div>
                            @endif
                            <div class="price-block">
                                @if($product->original_price && $product->original_price > $product->price)
                                    <span class="original-price">₹{{ number_format($product->original_price, 2) }}</span>
                                @endif
                                <span class="sale-price">₹{{ number_format($product->price, 2) }}</span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="footer">
            <p>© {{ date('Y') }} Radhe Crackers. All rights reserved.</p>
            <p>For orders and support, contact us via WhatsApp: +91 8807060809 / +91 9751048974</p>
        </div>
    </div>
</body>
</html> 