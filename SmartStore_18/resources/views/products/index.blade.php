@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <!-- Header -->
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Products</h5>
                            <p class="text-muted small mb-0">Manage your product inventory</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-warning d-flex align-items-center gap-2" onclick="showLowStockAlert()">
                                <i class="fas fa-exclamation-triangle"></i>
                                @if($products->where('stock_quantity', '<', 50)->count() > 0)
                                    @php
                                        $lowStockProduct = $products->where('stock_quantity', '<', 50)->first();
                                    @endphp
                                    {{ $lowStockProduct->name }} Low Stock
                                    <span class="badge bg-danger rounded-pill">
                                        {{ $products->where('stock_quantity', '<', 50)->count() }}
                                    </span>
                                @else
                                    Low Stock
                                @endif
                            </button>
                            <div class="search-wrapper">
                                <input type="text" class="form-control" placeholder="Search products...">
                                <i class="fas fa-search"></i>
                            </div>
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-2"></i>Add New Product
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">SKU</th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Price</th>
                                    <th class="px-4 py-3">Stock</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr class="product-row">
                                    <td class="px-4">{{ $product->sku }}</td>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="product-icon me-3">
                                                <i class="fas fa-box"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $product->name }}</h6>
                                                <small class="text-muted">{{ $product->category }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4">₱{{ number_format($product->price, 2) }}</td>
                                    <td class="px-4">
                                        <span class="stock-badge {{ $product->stock_quantity <= 10 ? 'low-stock' : '' }}">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    </td>
                                    <td class="px-4">
                                        <span class="status-badge status-{{ $product->status }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('products.edit', $product->id) }}" 
                                               class="btn btn-primary btn-action"
                                               data-tooltip="Edit Product">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('products.destroy', $product->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-danger btn-action" 
                                                        data-tooltip="Delete Product"
                                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-box-open fa-3x mb-3"></i>
                                            <h5>No Products Found</h5>
                                            <p class="text-muted">Start by adding your first product</p>
                                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                                Add New Product
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($products->hasPages())
                    <div class="d-flex justify-content-end p-4">
                        {{ $products->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/products/index.css') }}">
@endpush

@push('scripts')
<script id="low-stock-data" type="application/json">
    @json($products->where('stock_quantity', '<', 50))
</script>
<script src="{{ asset('js/products/index.js') }}"></script>
@endpush
@endsection