package com.example.smartstore1.fragments;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.example.smartstore1.R;

public class CategoriesFragment extends Fragment {
    private RecyclerView categoriesRecyclerView;
    private FloatingActionButton addCategoryFab;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_categories, container, false);

        // Initialize views
        categoriesRecyclerView = view.findViewById(R.id.categories_recycler);
        addCategoryFab = view.findViewById(R.id.add_category_fab);

        // Set up RecyclerView with grid layout
        categoriesRecyclerView.setLayoutManager(new GridLayoutManager(getContext(), 2));

        // Set up FAB click listener
        addCategoryFab.setOnClickListener(v -> showAddCategoryDialog());

        // Load categories
        loadCategories();

        return view;
    }

    private void showAddCategoryDialog() {
        // TODO: Implement add category dialog
    }

    private void loadCategories() {
        // TODO: Implement categories loading from Firebase
    }
}
