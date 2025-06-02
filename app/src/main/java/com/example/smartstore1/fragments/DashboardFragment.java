package com.example.smartstore1.fragments;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import android.widget.Toast;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;
import com.google.android.material.button.MaterialButton;
import com.google.android.material.progressindicator.LinearProgressIndicator;
import com.google.firebase.database.DataSnapshot;
import com.example.smartstore1.R;
import com.example.smartstore1.adapters.LowStockAdapter;
import com.example.smartstore1.adapters.SaleAdapter;
import com.example.smartstore1.database.firebase.FirebaseManager;
import com.example.smartstore1.database.firebase.ProductRepository;
import com.example.smartstore1.database.firebase.SaleRepository;
import com.example.smartstore1.models.Product;
import com.example.smartstore1.models.Sale;
import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.Locale;
import java.util.stream.Collectors;

public class DashboardFragment extends Fragment {
    private TextView todaySalesAmount;
    private TextView totalProductsValue;
    private TextView totalSalesValue;
    private RecyclerView recentSalesRecyclerView;
    private RecyclerView lowStockRecyclerView;
    private SwipeRefreshLayout swipeRefreshLayout;
    private LinearProgressIndicator progressIndicator;
    private MaterialButton viewAllSales;
    private MaterialButton viewAllProducts;
    
    private SaleAdapter recentSalesAdapter;
    private LowStockAdapter lowStockAdapter;
    private SaleRepository saleRepository;
    private ProductRepository productRepository;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_dashboard, container, false);
        
        FirebaseManager firebaseManager = FirebaseManager.getInstance();
        saleRepository = firebaseManager.getSaleRepository();
        productRepository = firebaseManager.getProductRepository();
        
        initializeViews(view);
        setupRecyclerViews();
        setupListeners();
        loadDashboardData();

        return view;
    }

    private void initializeViews(View view) {
        todaySalesAmount = view.findViewById(R.id.today_sales_amount);
        totalProductsValue = view.findViewById(R.id.total_products);
        totalSalesValue = view.findViewById(R.id.total_sales_value);
        recentSalesRecyclerView = view.findViewById(R.id.recent_sales_recycler);
        lowStockRecyclerView = view.findViewById(R.id.low_stock_recycler);
        swipeRefreshLayout = view.findViewById(R.id.swipe_refresh);
        progressIndicator = view.findViewById(R.id.progress_indicator);
        viewAllSales = view.findViewById(R.id.view_all_sales);
        viewAllProducts = view.findViewById(R.id.view_all_products);
    }

    private void setupRecyclerViews() {
        // Recent Sales RecyclerView
        recentSalesAdapter = new SaleAdapter(new ArrayList<>(), null);
        recentSalesRecyclerView.setLayoutManager(new LinearLayoutManager(getContext()));
        recentSalesRecyclerView.setAdapter(recentSalesAdapter);

        // Low Stock RecyclerView
        lowStockAdapter = new LowStockAdapter(new ArrayList<>());
        lowStockRecyclerView.setLayoutManager(new LinearLayoutManager(getContext()));
        lowStockRecyclerView.setAdapter(lowStockAdapter);
    }

    private void setupListeners() {
        swipeRefreshLayout.setOnRefreshListener(this::loadDashboardData);
        
        viewAllSales.setOnClickListener(v -> {
            // Navigate to SalesFragment
            getParentFragmentManager().beginTransaction()
                .replace(R.id.fragment_container, new SalesFragment())
                .addToBackStack(null)
                .commit();
        });
        
        viewAllProducts.setOnClickListener(v -> {
            // Navigate to ProductsFragment
            getParentFragmentManager().beginTransaction()
                .replace(R.id.fragment_container, new ProductsFragment())
                .addToBackStack(null)
                .commit();
        });
    }

    private void loadDashboardData() {
        showLoading(true);
        
        // Load products data
        productRepository.getAllProducts()
            .addOnSuccessListener(this::handleProductsData)
            .addOnFailureListener(e -> handleError("Failed to load products"));

        // Load sales data
        saleRepository.getAllSales()
            .addOnSuccessListener(this::handleSalesData)
            .addOnFailureListener(e -> handleError("Failed to load sales"));
    }

    private void handleProductsData(DataSnapshot dataSnapshot) {
        List<Product> products = new ArrayList<>();
        for (DataSnapshot snapshot : dataSnapshot.getChildren()) {
            Product product = snapshot.getValue(Product.class);
            if (product != null) {
                products.add(product);
            }
        }
        updateProductsData(products);
        updateLowStockProducts(products);
        showLoading(false);
    }

    private void handleSalesData(DataSnapshot dataSnapshot) {
        List<Sale> sales = new ArrayList<>();
        for (DataSnapshot snapshot : dataSnapshot.getChildren()) {
            Sale sale = snapshot.getValue(Sale.class);
            if (sale != null) {
                sales.add(sale);
            }
        }
        updateSalesData(sales);
        showLoading(false);
    }

    private void updateProductsData(List<Product> products) {
        totalProductsValue.setText(String.valueOf(products.size()));
    }

    private void updateLowStockProducts(List<Product> products) {
        // Filter products with low stock (below threshold of 10)
        List<Product> lowStockProducts = products.stream()
            .filter(product -> product.getStock() < 10)
            .collect(Collectors.toList());
        
        lowStockAdapter.updateProducts(lowStockProducts);
    }

    private void updateSalesData(List<Sale> sales) {
        // Calculate total sales value
        double totalSales = sales.stream()
            .mapToDouble(Sale::getTotalAmount)
            .sum();
        
        // Calculate today's sales
        Calendar calendar = Calendar.getInstance();
        calendar.set(Calendar.HOUR_OF_DAY, 0);
        calendar.set(Calendar.MINUTE, 0);
        calendar.set(Calendar.SECOND, 0);
        Date startOfDay = calendar.getTime();
        
        double todaySales = sales.stream()
            .filter(sale -> sale.getDate().after(startOfDay))
            .mapToDouble(Sale::getTotalAmount)
            .sum();
        
        // Format currency values
        NumberFormat currencyFormatter = NumberFormat.getCurrencyInstance(new Locale("en", "US"));
        totalSalesValue.setText(currencyFormatter.format(totalSales));
        todaySalesAmount.setText(currencyFormatter.format(todaySales));
        
        // Update recent sales (last 5 sales)
        List<Sale> recentSales = sales.stream()
            .sorted((s1, s2) -> s2.getDate().compareTo(s1.getDate()))
            .limit(5)
            .collect(Collectors.toList());
        
        recentSalesAdapter.updateSales(recentSales);
    }

    private void showLoading(boolean isLoading) {
        progressIndicator.setVisibility(isLoading ? View.VISIBLE : View.GONE);
        swipeRefreshLayout.setRefreshing(isLoading);
    }

    private void handleError(String message) {
        showLoading(false);
        if (getContext() != null) {
            Toast.makeText(getContext(), message, Toast.LENGTH_SHORT).show();
        }
    }
}
