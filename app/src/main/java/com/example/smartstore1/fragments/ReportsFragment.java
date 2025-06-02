package com.example.smartstore1.fragments;

import android.graphics.Color;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;
import androidx.fragment.app.Fragment;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;
import com.github.mikephil.charting.charts.BarChart;
import com.github.mikephil.charting.charts.LineChart;
import com.github.mikephil.charting.charts.PieChart;
import com.github.mikephil.charting.components.XAxis;
import com.github.mikephil.charting.data.BarData;
import com.github.mikephil.charting.data.BarDataSet;
import com.github.mikephil.charting.data.BarEntry;
import com.github.mikephil.charting.data.Entry;
import com.github.mikephil.charting.data.LineData;
import com.github.mikephil.charting.data.LineDataSet;
import com.github.mikephil.charting.data.PieData;
import com.github.mikephil.charting.data.PieDataSet;
import com.github.mikephil.charting.data.PieEntry;
import com.github.mikephil.charting.formatter.IndexAxisValueFormatter;
import com.google.android.material.card.MaterialCardView;
import com.google.android.material.progressindicator.LinearProgressIndicator;
import com.example.smartstore1.R;
import com.example.smartstore1.database.firebase.FirebaseManager;
import com.example.smartstore1.database.firebase.ProductRepository;
import com.example.smartstore1.database.firebase.SaleRepository;
import com.google.firebase.database.DataSnapshot;
import com.example.smartstore1.models.Product;
import com.example.smartstore1.models.Sale;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.Map;
import java.util.stream.Collectors;

public class ReportsFragment extends Fragment {
    private SwipeRefreshLayout swipeRefreshLayout;
    private LinearProgressIndicator progressIndicator;
    private MaterialCardView salesReportCard;
    private MaterialCardView inventoryReportCard;
    private MaterialCardView profitReportCard;
    private LineChart salesChart;
    private BarChart inventoryChart;
    private PieChart profitChart;
    
    private SaleRepository saleRepository;
    private ProductRepository productRepository;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_reports, container, false);
        
        FirebaseManager firebaseManager = FirebaseManager.getInstance();
        saleRepository = firebaseManager.getSaleRepository();
        productRepository = firebaseManager.getProductRepository();
        
        initializeViews(view);
        setupCharts();
        setupListeners();
        loadReportsData();

        return view;
    }

    private void initializeViews(View view) {
        swipeRefreshLayout = view.findViewById(R.id.swipe_refresh);
        progressIndicator = view.findViewById(R.id.progress_indicator);
        salesReportCard = view.findViewById(R.id.sales_report_card);
        inventoryReportCard = view.findViewById(R.id.inventory_report_card);
        profitReportCard = view.findViewById(R.id.profit_report_card);
        salesChart = view.findViewById(R.id.sales_chart);
        inventoryChart = view.findViewById(R.id.inventory_chart);
        profitChart = view.findViewById(R.id.profit_chart);
    }

    private void setupCharts() {
        // Setup Sales Line Chart
        salesChart.getDescription().setEnabled(false);
        salesChart.setDrawGridBackground(false);
        salesChart.getXAxis().setPosition(XAxis.XAxisPosition.BOTTOM);
        salesChart.getAxisRight().setEnabled(false);
        
        // Setup Inventory Bar Chart
        inventoryChart.getDescription().setEnabled(false);
        inventoryChart.setDrawGridBackground(false);
        inventoryChart.getXAxis().setPosition(XAxis.XAxisPosition.BOTTOM);
        inventoryChart.getAxisRight().setEnabled(false);
        
        // Setup Profit Pie Chart
        profitChart.getDescription().setEnabled(false);
        profitChart.setDrawHoleEnabled(true);
        profitChart.setHoleColor(Color.WHITE);
        profitChart.setTransparentCircleRadius(61f);
    }

    private void setupListeners() {
        swipeRefreshLayout.setOnRefreshListener(this::loadReportsData);
    }

    private List<Sale> convertDataSnapshotToSales(DataSnapshot dataSnapshot) {
        List<Sale> sales = new ArrayList<>();
        for (DataSnapshot snapshot : dataSnapshot.getChildren()) {
            Sale sale = snapshot.getValue(Sale.class);
            if (sale != null) {
                sales.add(sale);
            }
        }
        return sales;
    }

    private void loadReportsData() {
        showLoading(true);
        
        // Load Sales Data
        saleRepository.getAllSales()
            .addOnSuccessListener(dataSnapshot -> {
                List<Sale> sales = convertDataSnapshotToSales(dataSnapshot);
                updateSalesChart(sales);
                updateProfitChart(sales);
            })
            .addOnFailureListener(e -> 
                showError("Failed to load sales data: " + e.getMessage()));
    
        // Load Inventory Data
        productRepository.getAllProducts()
            .addOnSuccessListener(dataSnapshot -> {
                List<Product> products = convertDataSnapshotToProducts(dataSnapshot);
                updateInventoryChart(products);
                showLoading(false);
            })
            .addOnFailureListener(e -> {
                showError("Failed to load inventory data: " + e.getMessage());
                showLoading(false);
            });
    }

    private List<Product> convertDataSnapshotToProducts(DataSnapshot dataSnapshot) {
        List<Product> products = new ArrayList<>();
        for (DataSnapshot snapshot : dataSnapshot.getChildren()) {
            Product product = snapshot.getValue(Product.class);
            if (product != null) {
                products.add(product);
            }
        }
        return products;
    }

    private void updateSalesChart(List<Sale> sales) {
        List<Entry> entries = new ArrayList<>();
        // Group sales by day and create entries
        Map<Date, Double> dailySales = sales.stream()
            .collect(Collectors.groupingBy(
                Sale::getDate,
                Collectors.summingDouble(Sale::getTotal)
            ));

        int index = 0;
        for (Map.Entry<Date, Double> entry : dailySales.entrySet()) {
            entries.add(new Entry(index++, entry.getValue().floatValue()));
        }

        LineDataSet dataSet = new LineDataSet(entries, "Daily Sales");
        dataSet.setColor(Color.BLUE);
        dataSet.setValueTextColor(Color.BLACK);
        
        LineData lineData = new LineData(dataSet);
        salesChart.setData(lineData);
        salesChart.invalidate();
    }

    private void updateInventoryChart(List<Product> products) {
        List<BarEntry> entries = new ArrayList<>();
        List<String> labels = new ArrayList<>();
        
        int index = 0;
        for (Product product : products) {
            entries.add(new BarEntry(index, product.getStock()));
            labels.add(product.getName());
            index++;
        }

        BarDataSet dataSet = new BarDataSet(entries, "Stock Levels");
        dataSet.setColor(Color.GREEN);
        
        BarData barData = new BarData(dataSet);
        inventoryChart.getXAxis().setValueFormatter(new IndexAxisValueFormatter(labels));
        inventoryChart.setData(barData);
        inventoryChart.invalidate();
    }

    private void updateProfitChart(List<Sale> sales) {
        List<PieEntry> entries = new ArrayList<>();
        
        // Calculate total revenue and costs
        double totalRevenue = sales.stream()
            .mapToDouble(Sale::getTotal)
            .sum();
        double totalCosts = sales.stream()
            .mapToDouble(Sale::getCost)
            .sum();
        double profit = totalRevenue - totalCosts;

        entries.add(new PieEntry((float)profit, "Profit"));
        entries.add(new PieEntry((float)totalCosts, "Costs"));

        PieDataSet dataSet = new PieDataSet(entries, "Profit Analysis");
        ArrayList<Integer> colors = new ArrayList<>();
        colors.add(Color.rgb(76, 175, 80));  // Green for profit
        colors.add(Color.rgb(244, 67, 54));  // Red for costs
        dataSet.setColors(colors);

        PieData pieData = new PieData(dataSet);
        profitChart.setData(pieData);
        profitChart.invalidate();
    }

    private void showLoading(boolean isLoading) {
        swipeRefreshLayout.setRefreshing(isLoading);
        progressIndicator.setVisibility(isLoading ? View.VISIBLE : View.GONE);
    }

    private void showError(String message) {
        if (getContext() != null) {
            Toast.makeText(getContext(), message, Toast.LENGTH_LONG).show();
        }
    }
}
