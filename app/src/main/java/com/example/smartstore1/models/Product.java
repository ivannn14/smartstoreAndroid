package com.example.smartstore1.models;

public class Product {
    private String id;
    private String name;
    private double price;
    private int stock;
    private String category;
    private String description;
    private double cost;
    private String barcode;
    
    // Empty constructor needed for Firebase
    public Product() {}
    
    public Product(String id, String name, double price, int stock, String category, String description, double cost, String barcode) {
        this.id = id;
        this.name = name;
        this.price = price;
        this.stock = stock;
        this.category = category;
        this.description = description;
        this.cost = cost;
        this.barcode = barcode;
    }
    
    // Getters and Setters
    public String getId() { return id; }
    public void setId(String id) { this.id = id; }
    
    public String getName() { return name; }
    public void setName(String name) { this.name = name; }
    
    public double getPrice() { return price; }
    public void setPrice(double price) { this.price = price; }
    
    public int getStock() { return stock; }
    public void setStock(int stock) { this.stock = stock; }
    
    public String getCategory() { return category; }
    public void setCategory(String category) { this.category = category; }
    
    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }
    
    public double getCost() { return cost; }
    public void setCost(double cost) { this.cost = cost; }
    
    public String getBarcode() { return barcode; }
    public void setBarcode(String barcode) { this.barcode = barcode; }
    
    // Add this method for LowStockAdapter
    public int getQuantity() { return stock; }
}