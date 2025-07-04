package com.example.smartstore1.models;

public class Category {
    private String id;
    private String name;
    private String description;
    private int productCount;

    public Category() {
        // Required empty constructor for Firebase
    }

    public Category(String id, String name, String description) {
        this.id = id;
        this.name = name;
        this.description = description;
        this.productCount = 0;
    }

    // Getters and Setters
    public String getId() { return id; }
    public void setId(String id) { this.id = id; }

    public String getName() { return name; }
    public void setName(String name) { this.name = name; }

    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }

    public int getProductCount() { return productCount; }
    public void setProductCount(int productCount) { this.productCount = productCount; }
}
