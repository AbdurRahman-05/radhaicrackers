<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .status-active { color: #27ae60; }
        .status-inactive { color: #e74c3c; }
        .quantity-low { color: #f39c12; }
        .quantity-out { color: #e74c3c; }
        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .summary h3 {
            margin-top: 0;
            color: #333;
        }
        .summary-item {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Stock Report</h1>
        <p>Generated on: {{ $generated_at->format('d/m/Y H:i:s') }}</p>
        <p>Total Stocks: {{ $stocks->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Stock ID</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Status</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stocks as $stock)
            <tr>
                <td>#{{ $stock->id }}</td>
                <td>{{ $stock->item_name }}</td>
                <td>{{ $stock->category ?? 'N/A' }}</td>
                <td class="{{ $stock->quantity <= 0 ? 'quantity-out' : ($stock->quantity <= 10 ? 'quantity-low' : '') }}">
                    {{ $stock->quantity }}
                </td>
                <td>₹{{ number_format($stock->price, 2) }}</td>
                <td class="status-{{ $stock->is_active ? 'active' : 'inactive' }}">
                    {{ $stock->is_active ? 'Active' : 'Inactive' }}
                </td>
                <td>{{ $stock->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h3>Summary</h3>
        <div class="summary-item">
            <strong>Total Stocks:</strong> {{ $stocks->count() }}
        </div>
        <div class="summary-item">
            <strong>Active Stocks:</strong> {{ $stocks->where('is_active', true)->count() }}
        </div>
        <div class="summary-item">
            <strong>Inactive Stocks:</strong> {{ $stocks->where('is_active', false)->count() }}
        </div>
        <div class="summary-item">
            <strong>In Stock (>0):</strong> {{ $stocks->where('quantity', '>', 0)->count() }}
        </div>
        <div class="summary-item">
            <strong>Out of Stock (0):</strong> {{ $stocks->where('quantity', 0)->count() }}
        </div>
        <div class="summary-item">
            <strong>Low Stock (≤10):</strong> {{ $stocks->where('quantity', '<=', 10)->where('quantity', '>', 0)->count() }}
        </div>
        <div class="summary-item">
            <strong>Total Value:</strong> ₹{{ number_format($stocks->sum(function($stock) { return $stock->quantity * $stock->price; }), 2) }}
        </div>
    </div>
</body>
</html> 