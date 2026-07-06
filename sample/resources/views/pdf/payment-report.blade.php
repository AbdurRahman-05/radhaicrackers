<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payments Report</title>
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
        .status-verified { color: #27ae60; }
        .status-pending { color: #f39c12; }
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
        <h1>Payments Report</h1>
        <p>Generated on: {{ $generated_at->format('d/m/Y H:i:s') }}</p>
        <p>Total Payments: {{ $payments->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Order ID</th>
                <th>Customer</th>
                <th>UPI ID</th>
                <th>Transaction ID</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>#{{ $payment->id }}</td>
                <td>#{{ $payment->order->id ?? 'N/A' }}</td>
                <td>{{ $payment->order->user->name ?? 'N/A' }}</td>
                <td>{{ $payment->upi_id ?? 'N/A' }}</td>
                <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                <td>₹{{ number_format($payment->amount, 2) }}</td>
                <td class="status-{{ $payment->is_verified ? 'verified' : 'pending' }}">
                    {{ $payment->is_verified ? 'Verified' : 'Pending' }}
                </td>
                <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h3>Summary</h3>
        <div class="summary-item">
            <strong>Total Payments:</strong> {{ $payments->count() }}
        </div>
        <div class="summary-item">
            <strong>Total Amount:</strong> ₹{{ number_format($payments->sum('amount'), 2) }}
        </div>
        <div class="summary-item">
            <strong>Verified Payments:</strong> {{ $payments->where('is_verified', true)->count() }}
        </div>
        <div class="summary-item">
            <strong>Pending Payments:</strong> {{ $payments->where('is_verified', false)->count() }}
        </div>
        <div class="summary-item">
            <strong>Verified Amount:</strong> ₹{{ number_format($payments->where('is_verified', true)->sum('amount'), 2) }}
        </div>
        <div class="summary-item">
            <strong>Pending Amount:</strong> ₹{{ number_format($payments->where('is_verified', false)->sum('amount'), 2) }}
        </div>
    </div>
</body>
</html> 