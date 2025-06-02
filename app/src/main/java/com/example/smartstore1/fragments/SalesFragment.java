package com.example.smartstore1.fragments;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;
import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.android.material.dialog.MaterialAlertDialogBuilder;
import com.google.android.material.progressindicator.LinearProgressIndicator;
import com.google.firebase.database.DataSnapshot;
import com.example.smartstore1.R;
import com.example.smartstore1.adapters.SaleAdapter;
import com.example.smartstore1.database.firebase.FirebaseManager;
import com.example.smartstore1.database.firebase.SaleRepository;
import com.example.smartstore1.models.Sale;
import java.util.ArrayList;
import java.util.List;

public class SalesFragment extends Fragment implements SaleAdapter.SaleClickListener {
    private RecyclerView salesRecyclerView;
    private FloatingActionButton newSaleFab;
    private SwipeRefreshLayout swipeRefreshLayout;
    private LinearProgressIndicator progressIndicator;
    private SaleAdapter saleAdapter;
    private SaleRepository saleRepository;
    private View emptyStateView;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_sales, container, false);
        saleRepository = FirebaseManager.getInstance().getSaleRepository();
        
        initializeViews(view);
        setupRecyclerView();
        setupListeners();
        loadSales();

        return view;
    }

    private void initializeViews(View view) {
        salesRecyclerView = view.findViewById(R.id.sales_recycler);
        newSaleFab = view.findViewById(R.id.new_sale_fab);
        swipeRefreshLayout = view.findViewById(R.id.swipe_refresh);
        progressIndicator = view.findViewById(R.id.progress_indicator);
        emptyStateView = view.findViewById(R.id.empty_state);
    }

    private void setupRecyclerView() {
        saleAdapter = new SaleAdapter(new ArrayList<>(), this);
        salesRecyclerView.setLayoutManager(new LinearLayoutManager(getContext()));
        salesRecyclerView.setAdapter(saleAdapter);
    }

    private void setupListeners() {
        newSaleFab.setOnClickListener(v -> startNewSale());
        swipeRefreshLayout.setOnRefreshListener(this::loadSales);
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

    private void loadSales() {
        showLoading(true);
        saleRepository.getAllSales()
            .addOnSuccessListener(dataSnapshot -> {
                List<Sale> sales = convertDataSnapshotToSales(dataSnapshot);
                updateSalesList(sales);
                showLoading(false);
            })
            .addOnFailureListener(e -> {
                showError("Failed to load sales");
                showLoading(false);
            });
    }

    public void updateSalesList(List<Sale> sales) {
        if (sales.isEmpty()) {
            showEmptyState(true);
        } else {
            showEmptyState(false);
            saleAdapter.updateData(sales);
        }
    }

    private void startNewSale() {
        // TODO: Start POS activity or show new sale dialog
    }

    @Override
    public void onSaleClick(Sale sale) {
        // TODO: Show sale details
        // Navigate to SaleDetailsActivity with sale data
    }

    @Override
    public void onDeleteClick(Sale sale) {
        new MaterialAlertDialogBuilder(requireContext())
            .setTitle("Delete Sale")
            .setMessage("Are you sure you want to delete this sale record?")
            .setPositiveButton("Delete", (dialog, which) -> deleteSale(sale))
            .setNegativeButton("Cancel", null)
            .show();
    }

    private void deleteSale(Sale sale) {
        progressIndicator.setVisibility(View.VISIBLE);
        saleRepository.deleteSale(sale.getId())
            .addOnSuccessListener(aVoid -> {
                progressIndicator.setVisibility(View.GONE);
                Toast.makeText(getContext(), "Sale deleted successfully", Toast.LENGTH_SHORT).show();
                loadSales(); // Refresh the list
            })
            .addOnFailureListener(e -> {
                progressIndicator.setVisibility(View.GONE);
                showError("Failed to delete sale: " + e.getMessage());
            });
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

    private void showEmptyState(boolean show) {
        emptyStateView.setVisibility(show ? View.VISIBLE : View.GONE);
        salesRecyclerView.setVisibility(show ? View.GONE : View.VISIBLE);
    }
}
