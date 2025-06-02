@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/reports/stock.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('reports.index') }}" class="btn btn-outline-primary back-btn">
            <i class="fas fa-arrow-left me-2"></i>Back to Reports
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title">Stock Reports</h2>
                <div class="actions">
                    <button class="btn btn-primary me-2">
                        <i class="fas fa-download me-2"></i>Export Report
                    </button>
                    <button class="btn btn-success">
                        <i class="fas fa-sync-alt me-2"></i>Refresh Data
                    </button>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stats-info">
                            <h4>Total Items</h4>
                            <h3>2,459</h3>
                            <p class="trend positive">
                                <i class="fas fa-arrow-up"></i> 12% from last month
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stats-info">
                            <h4>Low Stock Items</h4>
                            <h3>28</h3>
                            <p class="trend negative">
                                <i class="fas fa-arrow-up"></i> 5% from last week
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon bg-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stats-info">
                            <h4>Out of Stock</h4>
                            <h3>12</h3>
                            <p class="trend neutral">
                                <i class="fas fa-minus"></i> No change
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon bg-success">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stats-info">
                            <h4>Stock Value</h4>
                            <h3>$124,500</h3>
                            <p class="trend positive">
                                <i class="fas fa-arrow-up"></i> 8% from last month
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">Inventory Overview</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                <input type="text" class="form-control w-50" placeholder="Search items...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>In Stock</th>
                                    <th>Reorder Level</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sample row, replace with dynamic data -->
                                <tr>
                                    <td>Sample Product</td>
                                    <td>SKU-001</td>
                                    <td>Electronics</td>
                                    <td>45</td>
                                    <td>20</td>
                                    <td><span class="badge bg-success">In Stock</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                        <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection