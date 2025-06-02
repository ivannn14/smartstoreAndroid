<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with('product')
                     ->orderBy('created_at', 'desc')
                     ->get();
                     
        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('sales.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            $product = Product::findOrFail($validated['product_id']);
            
            // Check if enough stock is available
            if ($product->stock < $validated['quantity']) {
                return back()->with('error', 'Insufficient stock available')->withInput();
            }

            // Create sale
            $sale = Sale::create([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price' => $validated['price'],
                'total_amount' => $validated['quantity'] * $validated['price'],
                'sale_datetime' => now(),
                'user_id' => auth()->id(),
                'payment_method' => 'cash' // Default to cash payment
            ]);
            
            // Update product stock
            $product->decrement('stock', $validated['quantity']);
            
            DB::commit();

            // Redirect to show view with success message
            return redirect()
                ->route('sales.show', $sale)
                ->with('success', 'Sale recorded successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'An error occurred while recording the sale')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load('product');
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $products = Product::all();
        return view('sales.edit', compact('sale', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $sale->update($validated);

        return redirect()
            ->route('sales.index')
            ->with('success', 'Sale updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();
            
            // Restore product stock
            $sale->product->increment('stock', $sale->quantity);
            
            // Delete the sale
            $sale->delete();
            
            DB::commit();

            return redirect()
                ->route('sales.index')
                ->with('success', 'Sale deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while deleting the sale');
        }
    }

    public function export()
    {
        $sales = Sale::with('product')->get();
        
        // Create CSV file
        $filename = 'sales_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename"
        ];

        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['ID', 'Product', 'Quantity', 'Price', 'Total', 'Date']);

        foreach ($sales as $sale) {
            fputcsv($handle, [
                $sale->id,
                $sale->product->name,
                $sale->quantity,
                $sale->price,
                $sale->total_amount,
                $sale->created_at->format('Y-m-d H:i:s')
            ]);
        }

        fclose($handle);

        return response()->stream(
            function() use ($handle) {
                fclose($handle);
            },
            200,
            $headers
        );
    }

    public function downloadReceipt(Sale $sale)
    {
        $pdf = PDF::loadView('sales.receipt', compact('sale'));
        
        return $pdf->download('receipt-' . $sale->id . '.pdf');
    }
}
