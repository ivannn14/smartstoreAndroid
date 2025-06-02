@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/reports/sales.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('reports.index') }}" class="btn btn-outline-primary back-btn">
            <i class="fas fa-arrow-left me-2"></i>Back to Reports
        </a>
    </div>

    <div class="card report-card">
        <!-- Header -->
        <div class="card-header py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="header-title">
                    <h2 class="mb-1">Sales Report</h2>
                    <p class="text-muted mb-0">Track your sales performance over time</p>
                </div>
                
                <div class="d-flex flex-wrap gap-2 controls-wrapper">
                    <form id="reportForm" class="d-flex flex-wrap gap-2">
                        <select name="period" class="form-select filter-control">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                        
                        <div class="input-group filter-control">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate ?? '' }}">
                        </div>
                        
                        <div class="input-group filter-control">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate ?? '' }}">
                        </div>
                        
                        <button type="submit" class="btn btn-primary apply-btn">
                            <i class="fas fa-filter me-2"></i>Apply
                        </button>
                    </form>

                    <button class="btn btn-success export-btn" onclick="exportReport()">
                        <i class="fas fa-file-export me-2"></i>Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="card-body">
            <div class="chart-container mb-4">
                <canvas id="salesChart" height="300"></canvas>
            </div>

            <!-- Stats Cards -->
            <div class="row g-3 mb-4 stats-container">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-details">
                            <h6 class="stat-label">Total Transactions</h6>
                            <h3 class="stat-value">{{ $totalTransactions }}</h3>
                            <p class="stat-change {{ $transactionsChange >= 0 ? 'positive' : 'negative' }}">
                                <i class="fas fa-arrow-{{ $transactionsChange >= 0 ? 'up' : 'down' }}"></i>
                                {{ number_format(abs($transactionsChange), 1) }}%
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-details">
                            <h6 class="stat-label">Total Sales</h6>
                            <h3 class="stat-value">₱{{ number_format($totalSales, 2) }}</h3>
                            <p class="stat-change positive">
                                <i class="fas fa-arrow-up"></i> 8.2%
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-details">
                            <h6 class="stat-label">Average Sale</h6>
                            <h3 class="stat-value">₱{{ number_format($averageSale, 2) }}</h3>
                            <p class="stat-change negative">
                                <i class="fas fa-arrow-down"></i> 3.1%
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="stat-details">
                            <h6 class="stat-label">Total Orders</h6>
                            <h6 class="stat-label">Unique Customers</h6>
                            <h3 class="stat-value">{{ $uniqueCustomers }}</h3>
                            <p class="stat-change positive">
                                <i class="fas fa-arrow-up"></i> 5.3%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="table-responsive table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Transactions</th>
                            <th>Total Sales</th>
                            <th>Average Sale</th>
                            <th>Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        <tr class="table-row">
                            <td>{{ $sale->date ?? $sale->week ?? $sale->month }}</td>
                            <td>{{ number_format($sale->total_transactions) }}</td>
                            <td>₱{{ number_format($sale->total_sales, 2) }}</td>
                            <td>₱{{ number_format($sale->total_sales / $sale->total_transactions, 2) }}</td>
                            <td>
                                <span class="trend-indicator positive">
                                    <i class="fas fa-arrow-up"></i> 2.4%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Card Styling */
.report-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    background: #1e1e2d; /* Darker background */
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.6s ease forwards;
}

.card-header {
    background: transparent;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    padding: 1.5rem;
}

/* Header Styling */
.header-title {
    opacity: 0;
    transform: translateY(-10px);
    animation: fadeInDown 0.6s ease 0.2s forwards;
}

.header-title h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
}

.header-title p {
    color: #cbd5e1;
    font-size: 1rem;
}

/* Controls Styling */
.controls-wrapper {
    opacity: 0;
    transform: translateY(-10px);
    animation: fadeInDown 0.6s ease 0.4s forwards;
}

.filter-control {
    background-color: #2a2a3c;
    border: 1px solid rgba(255,255,255,0.1);
    color: #ffffff;
    font-size: 0.95rem;
    min-width: 140px;
    transition: all 0.3s ease;
}

.filter-control:focus {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

/* Chart Container */
.chart-container {
    background: #2a2a3c;
    border-radius: 12px;
    padding: 1.5rem;
}

/* Stats Cards */
.stats-container {
    opacity: 0;
    animation: fadeInUp 0.6s ease 0.8s forwards;
}

.stat-card {
    background: #2a2a3c; /* Darker background for stat cards */
    border: none;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    background: linear-gradient(45deg, #ff0080, #7928ca);
}

.stat-icon.blue { background: linear-gradient(45deg, #2152ff, #21d4fd); }
.stat-icon.green { background: linear-gradient(45deg, #17ad37, #98ec2d); }
.stat-icon.purple { background: linear-gradient(45deg, #7928ca, #ff0080); }

.stat-details {
    flex: 1;
}

.stat-label {
    color: #cbd5e1;
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.25rem;
}

.stat-change {
    font-size: 0.875rem;
    margin: 0;
}

.stat-change.positive { color: #2dce89; }
.stat-change.negative { color: #f5365c; }

/* Table Styling */
.table-container {
    opacity: 0;
    animation: fadeInUp 0.6s ease 1s forwards;
}

.table-row {
    transition: all 0.3s ease;
}

.table-row:hover {
    background-color: #323248;
}

.trend-indicator {
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.trend-indicator.positive {
    background: rgba(45, 206, 137, 0.2);
    color: #4ade80;
}

.trend-indicator.negative {
    background: rgba(245, 54, 92, 0.2);
    color: #f87171;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Button Animations */
.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Dark Mode Support - Enhanced Contrast */
@media (prefers-color-scheme: dark) {
    .report-card {
        background: #1a1f37;
    }

    .card-header {
        border-bottom-color: rgba(255,255,255,0.05);
    }

    .header-title h2,
    .stat-value,
    .table {
        color: #ffffff;
    }

    .header-title p,
    .stat-label,
    .table tbody td {
        color: #cbd5e1;
    }

    .filter-control {
        background-color: #2d3748;
        border-color: #4a5568;
        color: #ffffff;
    }

    .table thead th {
        background-color: #2d3748;
        color: #ffffff;
    }

    .trend-indicator.positive {
        background: rgba(45, 206, 137, 0.2);
        color: #4ade80;
    }

    .trend-indicator.negative {
        background: rgba(245, 54, 92, 0.2);
        color: #f87171;
    }

    .chart-container {
        background: #2d3748;
    }
}

/* Light mode support */
@media (prefers-color-scheme: light) {
    .report-card {
        background: #ffffff;
    }

    .stat-card {
        background: #f8fafc;
    }

    .header-title h2 {
        color: #1a1f37;
    }

    .header-title p {
        color: #4b5563;
    }

    .stat-label {
        color: #4b5563;
    }

    .stat-value {
        color: #1a1f37;
    }

    .table {
        color: #1a1f37;
    }

    .table thead th {
        background-color: #f8fafc;
        color: #1a1f37;
    }

    .table tbody td {
        color: #4b5563;
    }

    .filter-control {
        background-color: #ffffff;
        border-color: #e5e7eb;
        color: #1a1f37;
    }

    .input-group-text {
        background-color: #f8fafc;
        border-color: #e5e7eb;
        color: #4b5563;
    }

    .chart-container {
        background: #f8fafc;
    }
}

/* Enhance text selection visibility */
::selection {
    background: #3b82f6;
    color: #ffffff;
}

/* Add these new styles */
.back-btn {
    transition: all 0.3s ease;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-weight: 500;
}

.back-btn:hover {
    transform: translateX(-5px);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pass PHP variables to JavaScript
    const chartData = {
        labels: @json($sales->pluck('date')),
        data: @json($sales->pluck('total_sales'))
    };
</script>
<script src="{{ asset('js/reports/sales.js') }}"></script>
@endpush
@endsection