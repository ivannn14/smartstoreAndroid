public function receipt()
{
    $latestSale = Sale::latest()->first();
    
    return view('pos.receipt', [
        'items' => [
            [
                'name' => $latestSale->product->name,
                'quantity' => $latestSale->quantity,
                'price' => $latestSale->price
            ]
        ],
        'subtotal' => $latestSale->total_amount,
        'tax' => $latestSale->total_amount * 0.12,
        'total' => $latestSale->total_amount * 1.12,
        'receiptNumber' => $latestSale->id,
        'paymentMethod' => $latestSale->payment_method
    ]);
}

public function index()
{
    // Get all sales with their related products, ordered by most recent first
    $sales = Sale::with('product')
                 ->orderBy('created_at', 'desc')
                 ->get();

    return view('sales.index', compact('sales'));
}