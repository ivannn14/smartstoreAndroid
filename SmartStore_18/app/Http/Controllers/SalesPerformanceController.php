<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesPerformanceController extends Controller
{
    public function index(Request $request)
    {
        $dateRange = $request->input('date_range', 'daily');
        $startDate = $request->input('start_date', Carbon::now()->startOfDay());
        $endDate = $request->input('end_date', Carbon::now()->endOfDay());

        // Convert dates to Carbon instances if they're strings
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate)->startOfDay();
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate)->endOfDay();
        }

        // Get sales statistics
        $statistics = $this->getSalesStatistics($startDate, $endDate);
        
        // Get sales data for graph
        $salesData = $this->getSalesData($startDate, $endDate, $dateRange);

        return view('sales.performance', compact('statistics', 'salesData', 'dateRange'));
    }

    private function getSalesStatistics($startDate, $endDate)
    {
        $currentPeriod = Sale::whereBetween('created_at', [$startDate, $endDate]);
        
        // Calculate previous period
        $periodDiff = $endDate->diffInSeconds($startDate);
        $previousStart = Carbon::parse($startDate)->subSeconds($periodDiff);
        $previousPeriod = Sale::whereBetween('created_at', [$previousStart, $startDate]);

        return [
            'total_transactions' => [
                'current' => $currentPeriod->count(),
                'previous' => $previousPeriod->count(),
                'growth' => $this->calculateGrowth(
                    $currentPeriod->count(),
                    $previousPeriod->count()
                )
            ],
            'total_sales' => [
                'current' => $currentPeriod->sum('total_amount'),
                'previous' => $previousPeriod->sum('total_amount'),
                'growth' => $this->calculateGrowth(
                    $currentPeriod->sum('total_amount'),
                    $previousPeriod->sum('total_amount')
                )
            ],
            'average_sale' => [
                'current' => $currentPeriod->avg('total_amount') ?? 0,
                'previous' => $previousPeriod->avg('total_amount') ?? 0,
                'growth' => $this->calculateGrowth(
                    $currentPeriod->avg('total_amount') ?? 0,
                    $previousPeriod->avg('total_amount') ?? 0
                )
            ],
            'unique_customers' => [
                'current' => $currentPeriod->distinct('customer_id')->count('customer_id'),
                'previous' => $previousPeriod->distinct('customer_id')->count('customer_id'),
                'growth' => $this->calculateGrowth(
                    $currentPeriod->distinct('customer_id')->count('customer_id'),
                    $previousPeriod->distinct('customer_id')->count('customer_id')
                )
            ]
        ];
    }

    private function getSalesData($startDate, $endDate, $dateRange)
    {
        $groupBy = match($dateRange) {
            'daily' => 'DATE(created_at)',
            'weekly' => 'YEARWEEK(created_at)',
            'monthly' => 'DATE_FORMAT(created_at, "%Y-%m")',
            default => 'DATE(created_at)'
        };

        return Sale::select(
            DB::raw("$groupBy as period"),
            DB::raw('COUNT(*) as transactions'),
            DB::raw('SUM(total_amount) as total_sales'),
            DB::raw('AVG(total_amount) as average_sale')
        )
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('period')
        ->orderBy('period')
        ->get();
    }

    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return (($current - $previous) / $previous) * 100;
    }

    public function export(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()));
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()));

        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->with(['customer', 'product'])
            ->get();

        $filename = 'sales_performance_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';
        
        return response()->streamDownload(function() use ($sales) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'Date',
                'Transaction ID',
                'Customer',
                'Product',
                'Quantity',
                'Unit Price',
                'Total Amount'
            ]);

            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->created_at->format('Y-m-d H:i:s'),
                    $sale->id,
                    $sale->customer->name ?? 'N/A',
                    $sale->product->name,
                    $sale->quantity,
                    $sale->price,
                    $sale->total_amount
                ]);
            }

            fclose($file);
        }, $filename);
    }
} 