package com.example.smartstore1.models;

import java.util.Date;
import java.util.List;

public class Sale {    private String id;
    private List<SaleItem> items;
    private double total;
    private double totalAmount; // For backward compatibility
    private double cost;
    private String paymentMethod;
    private String customerId;
    private Date date;

    public Sale() {}

    public Sale(String id, List<SaleItem> items, double totalAmount, String paymentMethod, String customerId) {
        this.id = id;
        this.items = items;
        this.total = totalAmount;
        this.totalAmount = totalAmount; // Keep both in sync
        this.paymentMethod = paymentMethod;
        this.customerId = customerId;
        this.date = new Date();
        this.cost = calculateCost();
    }

    public String getId() { return id; }
    public void setId(String id) { this.id = id; }

    public List<SaleItem> getItems() { return items; }
    public void setItems(List<SaleItem> items) { this.items = items; }

    public double getTotal() { return total; }
    public void setTotal(double total) { 
        this.total = total;
        this.totalAmount = total; // Keep both in sync
    }

    public double getCost() { return cost; }
    public void setCost(double cost) { this.cost = cost; }

    public String getPaymentMethod() { return paymentMethod; }
    public void setPaymentMethod(String paymentMethod) { this.paymentMethod = paymentMethod; }

    public double getTotalAmount() { return totalAmount; }
    public void setTotalAmount(double totalAmount) { 
        this.totalAmount = totalAmount;
        this.total = totalAmount; // Keep both in sync
    }

    public String getCustomerId() { return customerId; }
    public void setCustomerId(String customerId) { this.customerId = customerId; }

    public Date getDate() { return date; }
    public void setDate(Date date) { this.date = date; }

    // Alias for getDate() for backward compatibility
    public Date getSaleDate() { return getDate(); }

    private double calculateCost() {
        if (items == null) return 0;
        return items.stream()
                   .mapToDouble(SaleItem::getTotalCost)
                   .sum();
    }
}
