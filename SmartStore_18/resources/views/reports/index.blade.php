@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/reports/index.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="page-title mb-4">Reports & Analytics</h2>
            
            <div class="row g-4">
                <!-- Sales Reports Card -->
                <div class="col-md-4">
                    <div class="report-card">
                        <div class="card-content">
                            <div class="icon-wrapper">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="content">
                                <h3>Sales Reports</h3>
                                <p>View detailed sales reports with daily, weekly, and monthly filtering options.</p>
                                <a href="{{ route('reports.sales') }}" class="btn btn-primary">
                                    View Sales Reports
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stock Reports Card -->
                <div class="col-md-4">
                    <div class="report-card">
                        <div class="card-content">
                            <div class="icon-wrapper">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div class="content">
                                <h3>Stock Reports</h3>
                                <p>Monitor inventory levels and stock movement across categories.</p>
                                <a href="{{ route('reports.stock') }}" class="btn btn-warning">
                                    View Stock Reports
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Analytics Card -->
                <div class="col-md-4">
                    <div class="report-card">
                        <div class="card-content">
                            <div class="icon-wrapper">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="content">
                                <h3>Performance Analytics</h3>
                                <p>Analyze sales performance and trends over time.</p>
                                <a href="{{ route('reports.performance') }}" class="btn btn-success">
                                    View Performance
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection