package com.example.smartstore1.database.firebase;

import com.google.android.gms.tasks.Task;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.Query;
import com.example.smartstore1.models.Sale;
import com.example.smartstore1.utils.constants.AppConstants;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

public class SaleRepository implements FirebaseRepository<Sale> {
    private final DatabaseReference salesRef;

    public SaleRepository() {
        FirebaseDatabase database = FirebaseDatabase.getInstance();
        salesRef = database.getReference(AppConstants.REF_SALES);
    }

    @Override
    public Task<Void> add(Sale sale) {
        String saleId = salesRef.push().getKey();
        sale.setId(saleId);
        return salesRef.child(saleId).setValue(sale);
    }

    @Override
    public Task<Void> update(String id, Sale sale) {
        return salesRef.child(id).setValue(sale);
    }

    @Override
    public Task<Void> delete(String id) {
        return salesRef.child(id).removeValue();
    }

    @Override
    public Task<Sale> get(String id) {
        return salesRef.child(id).get()
            .continueWith(task -> {
                if (task.isSuccessful() && task.getResult() != null) {
                    return task.getResult().getValue(Sale.class);
                }
                return null;
            });
    }

    @Override
    public Task<List<Sale>> getAll() {
        return salesRef.get()
            .continueWith(task -> {
                List<Sale> sales = new ArrayList<>();
                if (task.isSuccessful() && task.getResult() != null) {
                    for (DataSnapshot snapshot : task.getResult().getChildren()) {
                        Sale sale = snapshot.getValue(Sale.class);
                        if (sale != null) {
                            sales.add(sale);
                        }
                    }
                }
                return sales;
            });
    }

    public Task<List<Sale>> getRecentSales(int limit) {
        Query query = salesRef.orderByChild("saleDate").limitToLast(limit);
        return query.get()
            .continueWith(task -> {
                List<Sale> sales = new ArrayList<>();
                if (task.isSuccessful() && task.getResult() != null) {
                    for (DataSnapshot snapshot : task.getResult().getChildren()) {
                        Sale sale = snapshot.getValue(Sale.class);
                        if (sale != null) {
                            sales.add(sale);
                        }
                    }
                }
                return sales;
            });
    }

    public Task<Double> getTotalSales() {
        return salesRef.get()
            .continueWith(task -> {
                double total = 0.0;
                if (task.isSuccessful() && task.getResult() != null) {
                    for (DataSnapshot snapshot : task.getResult().getChildren()) {
                        Sale sale = snapshot.getValue(Sale.class);
                        if (sale != null) {
                            total += sale.getTotalAmount();
                        }
                    }
                }
                return total;
            });
    }

    public Task<List<Sale>> getSalesByCustomer(String customerId) {
        Query query = salesRef.orderByChild("customerId").equalTo(customerId);
        return query.get()
            .continueWith(task -> {
                List<Sale> sales = new ArrayList<>();
                if (task.isSuccessful() && task.getResult() != null) {
                    for (DataSnapshot snapshot : task.getResult().getChildren()) {
                        Sale sale = snapshot.getValue(Sale.class);
                        if (sale != null) {
                            sales.add(sale);
                        }
                    }
                }
                return sales;
            });
    }

    public Task<DataSnapshot> getAllSales() {
        return salesRef.get();
    }

    public Task<Void> addSale(Sale sale) {
        if (sale.getId() == null) {
            sale.setId(salesRef.push().getKey());
        }
        return salesRef.child(sale.getId()).setValue(sale);
    }

    public Task<Void> deleteSale(String saleId) {
        return salesRef.child(saleId).removeValue();
    }

    public Task<List<Sale>> getSalesByDateRange(Date startDate, Date endDate) {
        Query query = salesRef.orderByChild("date")
                            .startAt(startDate.getTime())
                            .endAt(endDate.getTime());
        return query.get()
            .continueWith(task -> {
                List<Sale> sales = new ArrayList<>();
                if (task.isSuccessful() && task.getResult() != null) {
                    for (DataSnapshot snapshot : task.getResult().getChildren()) {
                        Sale sale = snapshot.getValue(Sale.class);
                        if (sale != null) {
                            sales.add(sale);
                        }
                    }
                }
                return sales;
            });
    }
}
