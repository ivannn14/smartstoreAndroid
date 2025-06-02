<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 'active')->get();
        $categories = Product::where('status', 'active')
                            ->distinct()
                            ->pluck('category')
                            ->filter()
                            ->values();
        
        return view('pos.index', compact('products', 'categories'));
    }

    public function checkout(Request $request)
    {
        // ... existing validation and calculation code ...

        foreach($items as $item) {
            Sale::create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total_amount' => $item['price'] * $item['quantity'],
                'payment_method' => $paymentMethod,
                'status' => 'completed'
            ]);
        }
    
        // Store receipt data in session
        $receiptData = [
            'items' => $items,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => $paymentMethod,
            'cash_amount' => $cashAmount ?? 0,
            'change' => $change ?? 0,
            'receipt_number' => $sale->id
        ];
        
        session(['receipt_data' => $receiptData]);
    
        // Redirect to sales index
        return redirect()->route('sales.index')->with('success', 'Sale completed successfully');
    }
}
