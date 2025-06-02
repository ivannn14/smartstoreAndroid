<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function salesReport(Request $request)
    {
        $period = $request->period ?? 'daily';
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->when($period === 'daily', function ($query) {
                return $query->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as total_transactions'),
                    DB::raw('SUM(total_amount) as total_sales')
                )->groupBy('date');
            })
            ->when($period === 'weekly', function ($query) {
                return $query->select(
                    DB::raw('YEARWEEK(created_at) as week'),
                    DB::raw('COUNT(*) as total_transactions'),
                    DB::raw('SUM(total_amount) as total_sales')
                )->groupBy('week');
            })
            ->when($period === 'monthly', function ($query) {
                return $query->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('COUNT(*) as total_transactions'),
                    DB::raw('SUM(total_amount) as total_sales')
                )->groupBy('month');
            })
            ->get();

        // Calculate totals for the stats cards
        $totalTransactions = $sales->sum('total_transactions');
        $totalSales = $sales->sum('total_sales');
        $averageSale = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;
        
        // Since we don't have customer_id, let's use transaction count as a proxy
        // or you can remove this stat entirely
        $uniqueCustomers = $totalTransactions; // Or remove if you don't want to show this

        // Calculate percentage changes (optional)
        $previousStartDate = (clone $startDate)->subMonth();
        $previousEndDate = (clone $endDate)->subMonth();

        $previousPeriod = Sale::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->select(
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(total_amount) as total_sales')
            )
            ->first();

        // Calculate percentage changes
        $transactionsChange = 0;
        $salesChange = 0;

        if ($previousPeriod) {
            $transactionsChange = $previousPeriod->total_transactions > 0 
                ? (($totalTransactions - $previousPeriod->total_transactions) / $previousPeriod->total_transactions) * 100 
                : 0;

            $salesChange = $previousPeriod->total_sales > 0 
                ? (($totalSales - $previousPeriod->total_sales) / $previousPeriod->total_sales) * 100 
                : 0;
        }

        return view('reports.sales', compact(
            'sales',
            'period',
            'startDate',
            'endDate',
            'totalTransactions',
            'totalSales',
            'averageSale',
            'uniqueCustomers',
            'transactionsChange',
            'salesChange'
        ));
    }

    public function stockReport()
    {
        return view('reports.stock');
    }

    public function performanceReport()
    {
        return view('reports.performance');
    }
}
