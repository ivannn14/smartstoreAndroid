<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Expense;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Calculate total sales
        $totalSales = Sale::sum('total_amount') ?? 0;

        // Get total inventory count
        $totalInventory = Product::sum('stock_quantity') ?? 0;

        // Get customer count

        // Calculate total expenses
        $totalExpenses = Expense::sum('amount') ?? 0;

        // Get low stock products (below 10 items)
        $lowStockProducts = Product::where('stock_quantity', '<', 10)->get();

        // Prepare sales data for chart
        $salesData = $this->getSalesChartData();

        return view('dashboard', compact(
            'totalSales',
            'totalInventory',
            'totalExpenses',
            'lowStockProducts',
            'salesData'
        ));
    }

    private function getSalesChartData()
    {
        $months = collect([]);
        $totals = collect([]);

        // Get sales data for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->format('M Y'));

            $monthSales = Sale::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');

            $totals->push($monthSales);
        }

        return [
            'months' => $months,
            'totals' => $totals
        ];
    }
}