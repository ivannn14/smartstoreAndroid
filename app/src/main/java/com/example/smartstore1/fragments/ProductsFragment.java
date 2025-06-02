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
import com.example.smartstore1.adapters.ProductAdapter;
import com.example.smartstore1.database.firebase.FirebaseManager;
import com.example.smartstore1.database.firebase.ProductRepository;
import com.example.smartstore1.models.Product;
import java.util.ArrayList;
import java.util.List;

public class ProductsFragment extends Fragment implements ProductAdapter.ProductClickListener {
    private RecyclerView productsRecyclerView;
    private FloatingActionButton addProductFab;
    private SwipeRefreshLayout swipeRefreshLayout;
    private LinearProgressIndicator progressIndicator;
    private ProductAdapter productAdapter;
    private ProductRepository productRepository;
    private View emptyStateView;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_products, container, false);
        productRepository = FirebaseManager.getInstance().getProductRepository();
        
        initializeViews(view);
        setupRecyclerView();
        setupListeners();
        loadProducts();

        return view;
    }

    private void initializeViews(View view) {
        productsRecyclerView = view.findViewById(R.id.products_recycler);
        addProductFab = view.findViewById(R.id.add_product_fab);
        swipeRefreshLayout = view.findViewById(R.id.swipe_refresh);
        progressIndicator = view.findViewById(R.id.progress_indicator);
        emptyStateView = view.findViewById(R.id.empty_state);
    }

    private void setupRecyclerView() {
        productAdapter = new ProductAdapter(new ArrayList<>(), this);
        productsRecyclerView.setLayoutManager(new LinearLayoutManager(getContext()));
        productsRecyclerView.setAdapter(productAdapter);
    }

    private void setupListeners() {
        addProductFab.setOnClickListener(v -> showAddProductDialog());
        swipeRefreshLayout.setOnRefreshListener(this::loadProducts);
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

    private void loadProducts() {
        showLoading(true);
        productRepository.getAllProducts()
            .addOnSuccessListener(dataSnapshot -> {
                List<Product> products = convertDataSnapshotToProducts(dataSnapshot);
                updateProductsList(products);
                showLoading(false);
            })
            .addOnFailureListener(e -> {
                showError("Failed to load products");
                showLoading(false);
            });
    }

    private void updateProductsList(List<Product> products) {
        if (products.isEmpty()) {
            showEmptyState(true);
        } else {
            showEmptyState(false);
            productAdapter.updateData(products);
        }
    }

    private void showAddProductDialog() {
        View dialogView = LayoutInflater.from(getContext()).inflate(R.layout.dialog_product, null);
        // TODO: Initialize dialog views and implement product addition
        new MaterialAlertDialogBuilder(requireContext())
            .setTitle("Add New Product")
            .setView(dialogView)
            .setPositiveButton("Add", (dialog, which) -> {
                // TODO: Validate and save product
            })
            .setNegativeButton("Cancel", null)
            .show();
    }

    @Override
    public void onProductClick(Product product) {
        // TODO: Show product details
    }

    @Override
    public void onEditClick(Product product) {
        // TODO: Show edit product dialog
    }

    @Override
    public void onDeleteClick(Product product) {
        new MaterialAlertDialogBuilder(requireContext())
            .setTitle("Delete Product")
            .setMessage("Are you sure you want to delete this product?")
            .setPositiveButton("Delete", (dialog, which) -> deleteProduct(product))
            .setNegativeButton("Cancel", null)
            .show();
    }

    private void deleteProduct(Product product) {
        progressIndicator.setVisibility(View.VISIBLE);
        productRepository.deleteProduct(product.getId())
            .addOnSuccessListener(aVoid -> {
                progressIndicator.setVisibility(View.GONE);
                Toast.makeText(getContext(), "Product deleted successfully", Toast.LENGTH_SHORT).show();
                loadProducts(); // Refresh the list
            })
            .addOnFailureListener(e -> {
                progressIndicator.setVisibility(View.GONE);
                showError("Failed to delete product: " + e.getMessage());
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
        productsRecyclerView.setVisibility(show ? View.GONE : View.VISIBLE);
    }
}
