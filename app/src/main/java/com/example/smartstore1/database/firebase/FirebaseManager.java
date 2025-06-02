package com.example.smartstore1.database.firebase;

import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.example.smartstore1.utils.constants.AppConstants;

public class FirebaseManager {
    private static FirebaseManager instance;
    private final FirebaseDatabase database;

    private ProductRepository productRepository;
    private CategoryRepository categoryRepository;
    private SaleRepository saleRepository;
    private CustomerRepository customerRepository;
    private ReceiptRepository receiptRepository;    private FirebaseManager() {
        database = FirebaseDatabase.getInstance();
    }

    public static synchronized FirebaseManager getInstance() {
        if (instance == null) {
            instance = new FirebaseManager();
        }
        return instance;
    }

    public ProductRepository getProductRepository() {
        if (productRepository == null) {
            productRepository = new ProductRepository();
        }
        return productRepository;
    }

    public CategoryRepository getCategoryRepository() {
        if (categoryRepository == null) {
            categoryRepository = new CategoryRepository();
        }
        return categoryRepository;
    }

    public SaleRepository getSaleRepository() {
        if (saleRepository == null) {
            saleRepository = new SaleRepository();
        }
        return saleRepository;
    }

    public CustomerRepository getCustomerRepository() {
        if (customerRepository == null) {
            customerRepository = new CustomerRepository();
        }
        return customerRepository;
    }

    public ReceiptRepository getReceiptRepository() {
        if (receiptRepository == null) {
            receiptRepository = new ReceiptRepository(database);
        }
        return receiptRepository;
    }

    public DatabaseReference getReference(String path) {
        return database.getReference(path);
    }
}
