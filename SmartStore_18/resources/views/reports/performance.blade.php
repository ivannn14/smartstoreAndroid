@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/reports/performance.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('reports.index') }}" class="btn btn-outline-primary back-btn">
            <i class="fas fa-arrow-left me-2"></i>Back to Reports
        </a>
    </div>

    <div class="card">
        <!-- Header -->
        <div class="card-header py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="header-title">
                    <h2 class="page-title">Performance Analytics</h2>
                    <p class="text-description">Track your business performance metrics</p>
                </div>
                
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-primary export-btn">
                        <i class="fas fa-download me-2"></i>Export Report
                    </button>
                    <button class="btn btn-success refresh-btn">
                        <i class="fas fa-sync-alt me-2"></i>Refresh Data
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Performance Metrics -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stats-info">
                            <h4>Revenue Growth</h4>
                            <h3>+24.5%</h3>
                            <p class="trend positive">
                                <i class="fas fa-arrow-up"></i> vs last month
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon bg-success">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stats-info">
                            <h4>Customer Growth</h4>
                            <h3>+12.3%</h3>
                            <p class="trend positive">
                                <i class="fas fa-arrow-up"></i> vs last month
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon bg-info">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stats-info">
                            <h4>Average Order Value</h4>
                            <h3>₱1,250</h3>
                            <p class="trend positive">
                                <i class="fas fa-arrow-up"></i> 5.2% increase
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-redo"></i>
                        </div>
                        <div class="stats-info">
                            <h4>Return Rate</h4>
                            <h3>2.4%</h3>
                            <p class="trend negative">
                                <i class="fas fa-arrow-down"></i> 0.5% decrease
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Chart -->
            <div class="chart-container mb-4">
                <canvas id="performanceChart" height="300"></canvas>
            </div>

            <!-- Performance Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Metric</th>
                            <th>Current</th>
                            <th>Previous</th>
                            <th>Change</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Revenue</td>
                            <td>₱245,678</td>
                            <td>₱198,456</td>
                            <td>+24.5%</td>
                            <td><span class="badge bg-success">Improved</span></td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/reports/performance.js') }}"></script>
@endpush