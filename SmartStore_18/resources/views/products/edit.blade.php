@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold">Edit Product</h5>
                        <a href="{{ route('products.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i> Back to Products
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('products.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        @if ($errors->any())
                        <div class="alert alert-danger bg-danger-subtle border-0 text-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-8 pe-4">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Product Name</label>
                                    <input type="text" 
                                           class="form-control form-control-lg bg-light" 
                                           name="name" 
                                           value="{{ old('name', $product->name) }}" 
                                           required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea class="form-control bg-light" 
                                              name="description" 
                                              rows="8" 
                                              style="resize: none;">{{ old('description', $product->description) }}</textarea>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Category</label>
                                        <input type="text" 
                                               class="form-control form-control-lg bg-light" 
                                               name="category" 
                                               value="{{ old('category', $product->category) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">SKU</label>
                                        <input type="text" 
                                               class="form-control form-control-lg bg-light" 
                                               name="sku" 
                                               value="{{ old('sku', $product->sku) }}" 
                                               required>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-4 ps-4 border-start">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Price</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light">₱</span>
                                        <input type="number" 
                                               class="form-control form-control-lg bg-light" 
                                               name="price" 
                                               value="{{ old('price', $product->price) }}" 
                                               step="0.01" 
                                               required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Stock Quantity</label>
                                    <input type="number" 
                                           class="form-control form-control-lg bg-light" 
                                           name="stock_quantity" 
                                           value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                                           min="0"
                                           required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Status</label>
                                    <select class="form-select form-select-lg bg-light" name="status" required>
                                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="button-group mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 update-button">
                                        Update Product
                                    </button>
                                    <button type="button" class="btn btn-light btn-lg w-100 cancel-button" 
                                            onclick="window.location.href='{{ route('products.index') }}'">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/products/edit.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/products/edit.js') }}"></script>
@endpush
@endsection