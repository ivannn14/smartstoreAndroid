@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-weight-bold">Add New Product</h5>
                        <a href="{{ route('products.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i> Back to Products
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
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
                                           value="{{ old('name') }}" 
                                           placeholder="Enter product name"
                                           required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea class="form-control bg-light" 
                                              name="description" 
                                              rows="8" 
                                              placeholder="Enter product description"
                                              style="resize: none;">{{ old('description') }}</textarea>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Category</label>
                                        <input type="text" 
                                               class="form-control form-control-lg bg-light" 
                                               name="category" 
                                               value="{{ old('category') }}" 
                                               placeholder="Enter category">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">SKU (Scan or Manual Input)</label>
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="form-control form-control-lg bg-light" 
                                                   name="sku" 
                                                   id="sku-input"
                                                   value="{{ old('sku') }}" 
                                                   placeholder="Enter or scan barcode"
                                                   pattern="[0-9]{12,13}"
                                                   title="Please enter a valid 12-13 digit barcode"
                                                   required
                                                   autocomplete="off">
                                            <button type="button" 
                                                    class="btn btn-primary" 
                                                    id="scan-button">
                                                <i class="fas fa-barcode me-2"></i>Scan
                                            </button>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                            <small class="text-muted">Enter manually or scan using camera</small>
                                            <small class="text-muted">Format: 12-13 digits</small>
                                        </div>
                                        <div id="barcode-feedback" class="form-text"></div>
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
                                               value="{{ old('price') }}" 
                                               step="0.01" 
                                               placeholder="0.00"
                                               required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Stock Quantity</label>
                                    <input type="number" 
                                           class="form-control form-control-lg bg-light" 
                                           name="stock_quantity" 
                                           value="{{ old('stock_quantity', 0) }}" 
                                           min="0"
                                           required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Status</label>
                                    <select class="form-select form-select-lg bg-light" name="status" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Product Image</label>
                                    <div class="d-flex flex-column align-items-center p-4 bg-light rounded">
                                        <img id="preview" src="{{ asset('images/placeholder.png') }}" 
                                             class="img-fluid mb-3" style="max-height: 200px; display: none;">
                                        <div class="upload-area w-100 text-center p-3 border-2 border-dashed rounded cursor-pointer">
                                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                            <p class="mb-0">Drag and drop an image or click to browse</p>
                                            <input type="file" name="image" id="image-input" class="d-none" 
                                                   accept="image/jpeg,image/png,image/jpg">
                                        </div>
                                    </div>
                                    <small class="text-muted">Maximum file size: 2MB. Supported formats: JPEG, PNG, JPG</small>
                                </div>

                                <div class="button-group mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 create-button">
                                        Create Product
                                    </button>
                                    <button type="button" class="btn btn-light btn-lg w-100 cancel-button" onclick="window.location.href='{{ route('products.index') }}'">
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

<!-- Add this modal for the barcode scanner -->
<div class="modal fade" id="scanner-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Barcode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="interactive" class="viewport"></div>
                <div class="error"></div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/products/create.css') }}">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const skuInput = document.getElementById('sku-input');
    const scanButton = document.getElementById('scan-button');
    const modal = new bootstrap.Modal(document.getElementById('scanner-modal'));
    const barcodeFeedback = document.getElementById('barcode-feedback');

    scanButton.addEventListener('click', function() {
        modal.show();
        initQuagga();
    });

    function initQuagga() {
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector("#interactive"),
                constraints: {
                    facingMode: "environment"
                },
            },
            decoder: {
                readers: [
                    "ean_reader",
                    "ean_8_reader",
                    "upc_reader",
                    "upc_e_reader"
                ]
            }
        }, function(err) {
            if (err) {
                console.error(err);
                document.querySelector(".error").textContent = "Error accessing camera: " + err.message;
                return;
            }
            Quagga.start();
        });

        Quagga.onDetected(function(result) {
            let code = result.codeResult.code;
            document.getElementById('sku-input').value = code;
            Quagga.stop();
            modal.hide();
        });
    }

    // Clean up when modal is hidden
    document.getElementById('scanner-modal').addEventListener('hidden.bs.modal', function() {
        Quagga.stop();
    });

    // Add loading state to form submission
    const form = document.querySelector('form');
    const createButton = document.querySelector('.create-button');
    
    form.addEventListener('submit', function() {
        createButton.classList.add('loading');
        createButton.innerHTML = '';  // Clear text while loading
    });

    // Optional: Add ripple effect to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('div');
            ripple.classList.add('ripple');
            this.appendChild(ripple);
            
            const rect = button.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            setTimeout(() => ripple.remove(), 1000);
        });
    });
});
</script>
@endpush
@endsection