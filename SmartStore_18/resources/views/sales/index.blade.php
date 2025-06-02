@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sales/index.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <!-- Header -->
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Sales List</h5>
                        <p class="text-muted small mb-0">Manage your sales records</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-animate" onclick="exportToExcel()">
                            <i class="fas fa-file-export me-2"></i>Export
                        </button>
                    </div>
                </div>

                <!-- Receipt Display Section (if available) -->
                @if(session('receipt_data'))
                <div class="card-body border-bottom">
                    <div class="receipt-preview bg-light p-4 rounded">
                        <div class="text-center mb-3">
                            <h4>SmartStore</h4>
                            <p class="small mb-1">{{ now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A') }}</p>
                            <p class="small">Receipt #: {{ session('receipt_data.receipt_number') }}</p>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(session('receipt_data.items') as $item)
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['quantity'] }}</td>
                                        <td>₱{{ number_format($item['price'], 2) }}</td>
                                        <td>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-end">
                            <p class="mb-1">Subtotal: ₱{{ number_format(session('receipt_data.subtotal'), 2) }}</p>
                            <p class="mb-1">Tax (12%): ₱{{ number_format(session('receipt_data.tax'), 2) }}</p>
                            <p class="fw-bold">Total: ₱{{ number_format(session('receipt_data.total'), 2) }}</p>
                            @if(session('receipt_data.payment_method') === 'cash')
                            <p class="mb-1">Cash: ₱{{ number_format(session('receipt_data.cash_amount'), 2) }}</p>
                            <p>Change: ₱{{ number_format(session('receipt_data.change'), 2) }}</p>
                            @else
                            <p>Paid by: Credit/Debit Card</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Filters -->
                <div class="card-body border-bottom">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search sales...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="dateFilter">
                                <option value="">All Dates</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td>
                                        <span class="fw-bold">#{{ $sale->id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($sale->product->image)
                                                <img src="{{ $sale->product->image }}" class="rounded me-2" width="40">
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $sale->product->name }}</div>
                                                <div class="text-muted small">{{ $sale->product->sku ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $sale->quantity }}</td>
                                    <td>₱{{ number_format($sale->price, 2) }}</td>
                                    <td class="fw-bold">₱{{ number_format($sale->total_amount, 2) }}</td>
                                    <td>
                                        <div>{{ $sale->created_at->format('M d, Y') }}</div>
                                        <div class="text-muted small">{{ $sale->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-completed">
                                            Completed
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('sales.show', $sale) }}" 
                                               class="btn btn-sm btn-info btn-animate">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger btn-animate"
                                                    onclick="deleteSale({{ $sale->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-box fa-2x mb-3"></i>
                                            <p>No sales records found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer -->
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing {{ $sales->count() }} entries
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<meta name="export-url" content="{{ route('sales.export') }}">
<meta name="receipt-url" content="{{ route('sales.receipt') }}">
<script src="{{ asset('js/sales/index.js') }}"></script>
@endpush
@endsection
