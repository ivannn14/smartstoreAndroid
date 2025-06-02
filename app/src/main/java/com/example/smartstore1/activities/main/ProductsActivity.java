package com.example.smartstore1.activities.main;

import android.os.Bundle;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.RecyclerView;
import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.example.smartstore1.R;
import com.example.smartstore1.adapters.LowStockAdapter;
import com.example.smartstore1.database.firebase.FirebaseManager;
import com.example.smartstore1.models.Product;

public class ProductsActivity extends AppCompatActivity {
    private RecyclerView recyclerView;
    private FloatingActionButton fabAddProduct;
    private FirebaseManager firebaseManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_products);

        firebaseManager = FirebaseManager.getInstance();
        initializeViews();
        setupRecyclerView();
        setupListeners();
    }

    private void initializeViews() {
        recyclerView = findViewById(R.id.productsRecyclerView);
        fabAddProduct = findViewById(R.id.fabAddProduct);
    }

    private void setupRecyclerView() {
        // Implement RecyclerView setup
    }

    private void setupListeners() {
        fabAddProduct.setOnClickListener(v -> showAddProductDialog());
    }

    private void showAddProductDialog() {
        // Implement add product dialog
    }
}
