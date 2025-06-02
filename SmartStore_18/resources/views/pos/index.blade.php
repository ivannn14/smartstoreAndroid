@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Left Sidebar - Categories -->
        <div class="col-lg-2">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0">Categories</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action active" data-category="all">
                            All Products
                        </a>
                        @foreach($categories as $category)
                        <a href="#" class="list-group-item list-group-item-action" data-category="{{ $category }}">
                            {{ $category }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Products</h5>
                        <div class="search-wrapper">
                            <input type="text" id="posSearch" class="form-control" placeholder="Search products...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3" id="products-grid">
                        @foreach($products as $product)
                        <div class="col-md-4 product-item" 
                             data-category="{{ $product->category }}"
                             data-price="{{ $product->price }}">
                            <div class="card product-card h-100">
                                <div class="card-body text-center">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             class="product-image mb-3" 
                                             alt="{{ $product->name }}">
                                    @else
                                        <div class="product-icon mb-3">
                                            <i class="fas fa-box fa-2x"></i>
                                        </div>
                                    @endif
                                    <h6 class="product-name">{{ $product->name }}</h6>
                                    <p class="text-muted small mb-2">{{ $product->category }}</p>
                                    <p class="price mb-0">₱{{ number_format($product->price, 2) }}</p>
                                    <small class="text-muted">Stock: {{ $product->stock_quantity }}</small>
                                    <button class="btn btn-primary mt-3 add-to-cart w-100" 
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-price="{{ $product->price }}"
                                            {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                        {{ $product->stock_quantity <= 0 ? 'Out of Stock' : 'Add to Cart' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart -->
        <div class="col-lg-3">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Shopping Cart</h5>
                </div>
                <div class="card-body">
                    <div id="cart-items" class="mb-3">
                        <!-- Cart items will be dynamically added here -->
                    </div>
                    <div class="cart-summary">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">₱0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Tax (12%):</span>
                            <span id="tax">₱0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total:</span>
                            <span id="total" class="fw-bold">₱0.00</span>
                        </div>
                        <button class="btn btn-primary w-100 mb-2" id="checkout-btn">
                            Proceed to Checkout
                        </button>
                        <button class="btn btn-light w-100" id="clear-cart-btn">
                            Clear Cart
                        </button>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" id="payment-method">
                            <option value="cash">Cash</option>
                            <option value="card">Credit/Debit Card</option>
                        </select>
                    </div>
                    <div class="mb-3" id="cash-payment">
                        <label class="form-label">Cash Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control" id="cash-amount" placeholder="0.00">
                        </div>
                        <div class="text-end mt-2">
                            <small class="text-muted">Change: <span id="change-amount">₱0.00</span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pos/index.css') }}">
@endpush

@push('scripts')
<script id="low-stock-data" type="application/json">
    @json($products->where('stock_quantity', '<', 50))
</script>
<script src="{{ asset('js/pos/index.js') }}"></script>
@endpush
@endsection