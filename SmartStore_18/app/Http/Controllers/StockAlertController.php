<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StockAlertController extends Controller
{
    public function index()
    {
        // Get all products
        $products = Product::all();
        
        return view('stock-alerts.index', [
            'products' => $products,
            'lowStockCount' => $products->where('stock_quantity', '<=', 10)->count()
        ]);
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock_quantity' => 'required|numeric|min:0'
        ]);

        $product->update([
            'stock_quantity' => $request->stock_quantity
        ]);

        return redirect()->back()->with('success', 'Stock updated successfully');
    }

    public function restock(Request $request, Product $product)
    {
        try {
            $product->increment('stock_quantity', 100);
            return redirect()->back()->with('success', 'Added 100 units to stock successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to restock product');
        }
    }
}
