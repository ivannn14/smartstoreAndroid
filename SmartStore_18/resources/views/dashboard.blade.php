@extends('layouts.app')

@section('title', 'Dashboard - SmartStore')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard/index.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Stats Container -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-light shadow-sm">
                <div class="card-header bg-transparent">
                    <h6 class="text-uppercase text-muted ls-1 mb-0">Overview</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Sales Card -->
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="card card-stats mb-4 mb-xl-0 shadow-sm border">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold text-muted">Total Sales</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    ₱{{ number_format($totalSales, 2) }}
                                                    <span class="text-success text-sm font-weight-bolder">+55%</span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                                <i class="fas fa-money-bill text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Card -->
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="card card-stats mb-4 mb-xl-0 shadow-sm border">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold text-muted">Total Inventory</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $totalInventory }}
                                                    <span class="text-danger text-sm font-weight-bolder">-2%</span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                                <i class="fas fa-boxes text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Expenses Card -->
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="card card-stats mb-4 mb-xl-0 shadow-sm border">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold text-muted">Total Expenses</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    ₱{{ number_format($totalExpenses, 2) }}
                                                    <span class="text-success text-sm font-weight-bolder">-3%</span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                                <i class="fas fa-receipt text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Container -->
    <div class="row">
        <!-- Sales Chart -->
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-gradient text-primary">Sales Overview</h6>
                        <div>
                            <select class="form-select form-select-sm me-2 d-inline-block w-auto">
                                <option value="7">Last 7 days</option>
                                <option value="30">Last 30 days</option>
                                <option value="90">Last 90 days</option>
                            </select>
                            <button class="btn btn-sm btn-primary">
                                <i class="fas fa-plus fa-fw"></i> Add Sale
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="salesChart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Alerts -->
        <div class="col-lg-4">
            <div class="card shadow-lg">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-gradient text-primary">Stock Alerts</h6>
                        <button class="btn btn-sm btn-warning">
                            <i class="fas fa-qrcode"></i> Scan Product
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="list-group">
                        @foreach ($lowStockProducts as $product)
                            <div class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-warning shadow text-center">
                                        <i class="fas fa-box text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">{{ $product->name }}</h6>
                                        <span class="text-xs">Category: {{ $product->category }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center text-danger text-gradient text-sm font-weight-bold">
                                    {{ $product->stock_quantity }} left
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script id="sales-data" type="application/json">
    @json(['months' => $salesData['months'], 'totals' => $salesData['totals']])
</script>
<script src="{{ asset('js/dashboard/index.js') }}"></script>
@endpush