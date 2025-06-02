<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt</title>
    <link rel="stylesheet" href="{{ asset('css/pos/receipt.css') }}">
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h2>SmartStore</h2>
            <p>{{ now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A') }}</p>
            <p>Receipt #: {{ $receiptNumber }}</p>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>P{{ number_format($item['price'], 2) }}</td>
                    <td>P{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <p>Subtotal: P{{ number_format($subtotal, 2) }}</p>
            <p>Tax (12%): P{{ number_format($tax, 2) }}</p>
            <p><strong>Total: P{{ number_format($total, 2) }}</strong></p>
            @if($paymentMethod === 'cash')
            <p>Cash: P{{ number_format($cashAmount, 2) }}</p>
            <p>Change: P{{ number_format($change, 2) }}</p>
            @else
            <p>Paid by: Credit/Debit Card</p>
            @endif
        </div>

        <div class="footer">
            <p>Thank you for shopping with us!</p>
            <p>Please come again</p>
        </div>
    </div>
</body>
</html>