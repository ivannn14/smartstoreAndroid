<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'category' => 'nullable|string|max:255',
            'sku' => 'required|string|min:12|max:13|unique:products', // Add this line
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'nullable|string',
            'sku' => 'required|unique:products,sku,'.$product->id,
            'status' => 'required|in:active,inactive',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function uploadImage(Request $request, Product $product)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            // Store new image
            $path = $request->file('image')->store('products', 'public');
            $product->update(['image' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'path' => Storage::url($path)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image file provided'
        ], 400);
    }

    public function deleteImage(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
            $product->update(['image' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image to delete'
        ], 400);
    }

    public function getProductByBarcode(Request $request)
    {
        $barcode = trim($request->input('barcode'));
        
        if (!Product::validateBarcode($barcode)) {
            return response()->json([
                'message' => 'Invalid barcode format',
                'valid' => false
            ], 400);
        }

        $product = Product::where('barcode', $barcode)->first();

        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
                'valid' => true,
                'exists' => false
            ], 404);
        }

        return response()->json([
            'message' => 'Product found',
            'valid' => true,
            'exists' => true,
            'product' => $product
        ]);
    }

    public function updateStock(Request $request)
    {
        try {
            foreach ($request->items as $item) {
                $product = Product::find($item['id']);
                if ($product) {
                    $product->stock_quantity -= $item['quantity'];
                    $product->save();
                }
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
