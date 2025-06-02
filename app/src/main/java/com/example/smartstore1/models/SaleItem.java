package com.example.smartstore1.models;

public class SaleItem {
    private String productId;
    private String productName;
    private int quantity;
    private double price;
    private double cost;

    public SaleItem() {}

    public SaleItem(String productId, String productName, int quantity, double price, double cost) {
        this.productId = productId;
        this.productName = productName;
        this.quantity = quantity;
        this.price = price;
        this.cost = cost;
    }

    public String getProductId() { return productId; }
    public void setProductId(String productId) { this.productId = productId; }

    public String getProductName() { return productName; }
    public void setProductName(String productName) { this.productName = productName; }

    public int getQuantity() { return quantity; }
    public void setQuantity(int quantity) { this.quantity = quantity; }

    public double getPrice() { return price; }
    public void setPrice(double price) { this.price = price; }

    public double getCost() { return cost; }
    public void setCost(double cost) { this.cost = cost; }

    public double getTotal() {
        return price * quantity;
    }

    public double getTotalCost() {
        return cost * quantity;
    }

    public Product getProduct() {
        return new Product(productId, productName, price, 0, "", "", cost, "");
    }
}
