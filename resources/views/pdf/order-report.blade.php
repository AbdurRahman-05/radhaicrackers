<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders Report</title>
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
        .status-pending { color: #f39c12; }
        .status-confirmed { color: #3498db; }
        .status-dispatched { color: #9b59b6; }
        .status-completed { color: #27ae60; }
        .status-cancelled { color: #e74c3c; }
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
        <h1>Orders Report</h1>
        <p>Generated on: {{ $generated_at->format('d/m/Y H:i:s') }}</p>
        <p>Total Orders: {{ $orders->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->user->name ?? 'N/A' }}</td>
                <td>{{ $order->user->phone ?? 'N/A' }}</td>
                <td>₹{{ number_format($order->total, 2) }}</td>
                <td class="status-{{ $order->status }}">{{ ucfirst($order->status) }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h3>Summary</h3>
        <div class="summary-item">
            <strong>Total Orders:</strong> {{ $orders->count() }}
        </div>
        <div class="summary-item">
            <strong>Total Amount:</strong> ₹{{ number_format($orders->sum('total'), 2) }}
        </div>
        <div class="summary-item">
            <strong>Pending Orders:</strong> {{ $orders->where('status', 'pending')->count() }}
        </div>
        <div class="summary-item">
            <strong>Confirmed Orders:</strong> {{ $orders->where('status', 'confirmed')->count() }}
        </div>
        <div class="summary-item">
            <strong>Dispatched Orders:</strong> {{ $orders->where('status', 'dispatched')->count() }}
        </div>
        <div class="summary-item">
            <strong>Completed Orders:</strong> {{ $orders->where('status', 'completed')->count() }}
        </div>
    </div>
</body>
</html> 