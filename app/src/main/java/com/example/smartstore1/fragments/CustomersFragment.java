package com.example.smartstore1.fragments;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.example.smartstore1.R;

public class CustomersFragment extends Fragment {
    private RecyclerView customersRecyclerView;
    private FloatingActionButton addCustomerFab;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_customers, container, false);

        // Initialize views
        customersRecyclerView = view.findViewById(R.id.customers_recycler);
        addCustomerFab = view.findViewById(R.id.add_customer_fab);

        // Set up RecyclerView
        customersRecyclerView.setLayoutManager(new LinearLayoutManager(getContext()));

        // Set up FAB click listener
        addCustomerFab.setOnClickListener(v -> showAddCustomerDialog());

        // Load customers
        loadCustomers();

        return view;
    }

    private void showAddCustomerDialog() {
        // TODO: Implement add customer dialog
    }

    private void loadCustomers() {
        // TODO: Implement customers loading from Firebase
    }
}
