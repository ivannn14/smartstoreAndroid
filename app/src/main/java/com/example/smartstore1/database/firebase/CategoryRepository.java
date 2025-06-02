package com.example.smartstore1.database.firebase;

import com.google.android.gms.tasks.Task;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.Query;
import com.example.smartstore1.models.Category;
import com.example.smartstore1.utils.constants.AppConstants;
import java.util.ArrayList;
import java.util.List;

public class CategoryRepository implements FirebaseRepository<Category> {
    private final DatabaseReference categoriesRef;

    public CategoryRepository() {
        FirebaseDatabase database = FirebaseDatabase.getInstance();
        categoriesRef = database.getReference(AppConstants.REF_CATEGORIES);
    }

    @Override
    public Task<Void> add(Category category) {
        String categoryId = categoriesRef.push().getKey();
        category.setId(categoryId);
        return categoriesRef.child(categoryId).setValue(category);
    }

    @Override
    public Task<Void> update(String id, Category category) {
        return categoriesRef.child(id).setValue(category);
    }

    @Override
    public Task<Void> delete(String id) {
        return categoriesRef.child(id).removeValue();
    }

    @Override
    public Task<Category> get(String id) {
        return categoriesRef.child(id).get()
            .continueWith(task -> {
                if (task.isSuccessful() && task.getResult() != null) {
                    return task.getResult().getValue(Category.class);
                }
                return null;
            });
    }

    @Override
    public Task<List<Category>> getAll() {
        return categoriesRef.get()
            .continueWith(task -> {
                List<Category> categories = new ArrayList<>();
                if (task.isSuccessful() && task.getResult() != null) {
                    for (DataSnapshot snapshot : task.getResult().getChildren()) {
                        Category category = snapshot.getValue(Category.class);
                        if (category != null) {
                            categories.add(category);
                        }
                    }
                }
                return categories;
            });
    }

    public Task<Void> updateProductCount(String categoryId, int count) {
        return categoriesRef.child(categoryId).child("productCount").setValue(count);
    }

    public Task<DataSnapshot> getAllCategories() {
        return categoriesRef.get();
    }

    public Task<Void> addCategory(Category category) {
        return categoriesRef.child(category.getId()).setValue(category);
    }

    public Task<Void> updateCategory(Category category) {
        return categoriesRef.child(category.getId()).setValue(category);
    }

    public Task<Void> deleteCategory(String categoryId) {
        return categoriesRef.child(categoryId).removeValue();
    }

    public Task<DataSnapshot> getCategory(String categoryId) {
        return categoriesRef.child(categoryId).get();
    }
}
