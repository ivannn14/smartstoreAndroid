package com.example.smartstore1.database.firebase;

import com.google.android.gms.tasks.Task;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.example.smartstore1.models.Product;
import com.example.smartstore1.utils.constants.AppConstants;
import java.util.ArrayList;
import java.util.List;

public class ProductRepository implements FirebaseRepository<Product> {
    private final DatabaseReference productsRef;

    public ProductRepository() {
        FirebaseDatabase database = FirebaseDatabase.getInstance();
        productsRef = database.getReference(AppConstants.REF_PRODUCTS);
    }

    @Override
    public Task<Void> add(Product product) {
        String productId = productsRef.push().getKey();
        product.setId(productId);
        return productsRef.child(productId).setValue(product);
    }

    @Override
    public Task<Void> update(String id, Product product) {
        return productsRef.child(id).setValue(product);
    }

    @Override
    public Task<Void> delete(String id) {
        return productsRef.child(id).removeValue();
    }

    @Override
    public Task<Product> get(String id) {
        return productsRef.child(id).get()
            .continueWith(task -> {
                if (task.isSuccessful() && task.getResult() != null) {
                    return task.getResult().getValue(Product.class);
                }
                return null;
            });
    }

    @Override
    public Task<List<Product>> getAll() {
        return productsRef.get()
            .continueWith(task -> {
                List<Product> products = new ArrayList<>();
                if (task.isSuccessful() && task.getResult() != null) {
                    for (DataSnapshot snapshot : task.getResult().getChildren()) {
                        Product product = snapshot.getValue(Product.class);
                        if (product != null) {
                            products.add(product);
                        }
                    }
                }
                return products;
            });
    }

    public Task<List<Product>> getLowStockProducts(int threshold) {
        return productsRef.orderByChild("stock").endAt(threshold).get()
            .continueWith(task -> {
                List<Product> products = new ArrayList<>();
                if (task.isSuccessful() && task.getResult() != null) {
                    for (DataSnapshot snapshot : task.getResult().getChildren()) {
                        Product product = snapshot.getValue(Product.class);
                        if (product != null) {
                            products.add(product);
                        }
                    }
                }
                return products;
            });
    }

    public Task<DataSnapshot> getAllProducts() {
        return productsRef.get();
    }

    public Task<DataSnapshot> getProduct(String productId) {
        return productsRef.child(productId).get();
    }

    public Task<DataSnapshot> getProductsByCategory(String categoryId) {
        return productsRef.orderByChild("categoryId").equalTo(categoryId).get();
    }

    public Task<Void> updateProduct(Product product) {
        return productsRef.child(product.getId()).setValue(product);
    }

    public Task<Void> deleteProduct(String productId) {
        return productsRef.child(productId).removeValue();
    }

    public Task<List<Product>> searchProducts(String query) {
        return productsRef.get()
            .continueWith(task -> {
                List<Product> products = new ArrayList<>();
                if (task.isSuccessful() && task.getResult() != null) {
                    for (DataSnapshot snapshot : task.getResult().getChildren()) {
                        Product product = snapshot.getValue(Product.class);
                        if (product != null && 
                            (product.getName().toLowerCase().contains(query.toLowerCase()) ||
                             product.getBarcode().contains(query))) {
                            products.add(product);
                        }
                    }
                }
                return products;
            });
    }
}
