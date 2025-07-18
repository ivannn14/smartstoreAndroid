package com.example.smartstore1.models;

public class Customer {
    private String id;
    private String name;
    private String email;
    private String phone;
    private String address;
    private double totalPurchases;

    public Customer() {
        // Required empty constructor for Firebase
        this.totalPurchases = 0.0;
    }

    public Customer(String id, String name, String email, String phone, String address) {
        this.id = id;
        this.name = name;
        this.email = email;
        this.phone = phone;
        this.address = address;
        this.totalPurchases = 0.0;
    }

    // Getters and Setters
    public String getId() { return id; }
    public void setId(String id) { this.id = id; }

    public String getName() { return name; }
    public void setName(String name) { this.name = name; }

    public String getEmail() { return email; }
    public void setEmail(String email) { this.email = email; }

    public String getPhone() { return phone; }
    public void setPhone(String phone) { this.phone = phone; }

    public String getAddress() { return address; }
    public void setAddress(String address) { this.address = address; }

    public double getTotalPurchases() { return totalPurchases; }
    public void setTotalPurchases(double totalPurchases) { this.totalPurchases = totalPurchases; }

    public void addPurchase(double amount) {
        this.totalPurchases += amount;
    }
}
