package com.example.smartstore1.database.firebase;

import com.google.android.gms.tasks.Task;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.Query;
import com.example.smartstore1.models.Customer;
import com.example.smartstore1.utils.constants.AppConstants;
import java.util.ArrayList;
import java.util.List;

public class CustomerRepository implements FirebaseRepository<Customer> {
    private final DatabaseReference customersRef;

    public CustomerRepository() {
        FirebaseDatabase database = FirebaseDatabase.getInstance();
        customersRef = database.getReference(AppConstants.REF_CUSTOMERS);
    }

    @Override
    public Task<Void> add(Customer customer) {
        String customerId = customersRef.push().getKey();
        customer.setId(customerId);
        return customersRef.child(customerId).setValue(customer);
    }

    @Override
    public Task<Void> update(String id, Customer customer) {
        return customersRef.child(id).setValue(customer);
    }

    @Override
    public Task<Void> delete(String id) {
        return customersRef.child(id).removeValue();
    }

    @Override
    public Task<Customer> get(String id) {
        return customersRef.child(id).get()
            .continueWith(task -> {
                if (task.isSuccessful() && task.getResult() != null) {
                    return task.getResult().getValue(Customer.class);
                }
                return null;
            });
    }

    @Override
    public Task<List<Customer>> getAll() {
        return customersRef.get()
            .continueWith(task -> {
                List<Customer> customers = new ArrayList<>();
                if (task.isSuccessful() && task.getResult() != null) {
                    for (DataSnapshot snapshot : task.getResult().getChildren()) {
                        Customer customer = snapshot.getValue(Customer.class);
                        if (customer != null) {
                            customers.add(customer);
                        }
                    }
                }
                return customers;
            });
    }

    public Task<List<Customer>> getTopCustomers(int limit) {
        Query query = customersRef.orderByChild("totalPurchases").limitToLast(limit);
        return query.get()
            .continueWith(task -> {
                List<Customer> customers = new ArrayList<>();
                if (task.isSuccessful() && task.getResult() != null) {
                    for (DataSnapshot snapshot : task.getResult().getChildren()) {
                        Customer customer = snapshot.getValue(Customer.class);
                        if (customer != null) {
                            customers.add(customer);
                        }
                    }
                }
                return customers;
            });
    }

    public Task<Void> updateTotalPurchases(String customerId, double amount) {
        return customersRef.child(customerId).child("totalPurchases").setValue(amount);
    }

    public Task<Void> addCustomer(Customer customer) {
        return customersRef.child(customer.getId()).setValue(customer);
    }

    public Task<Void> updateCustomer(Customer customer) {
        return customersRef.child(customer.getId()).setValue(customer);
    }

    public Task<Void> deleteCustomer(String customerId) {
        return customersRef.child(customerId).removeValue();
    }

    public Task<DataSnapshot> getCustomer(String customerId) {
        return customersRef.child(customerId).get();
    }

    public Task<DataSnapshot> getAllCustomers() {
        return customersRef.get();
    }

    public Task<DataSnapshot> searchCustomers(String query) {
        // Firebase doesn't support direct text search, so we'll fetch all and filter client-side
        return customersRef.get();
    }
}
