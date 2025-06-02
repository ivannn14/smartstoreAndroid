package com.example.smartstore1.utils;

public class AppConstants {
    // Firebase Database References
    public static final String REF_USERS = "users";
    public static final String REF_PRODUCTS = "products";
    public static final String REF_CATEGORIES = "categories";
    public static final String REF_SALES = "sales";
    public static final String REF_RECEIPTS = "receipts";
    public static final String REF_CUSTOMERS = "customers";
    public static final String REF_COMPANY_PROFILE = "company_profile";

    // Intent Keys
    public static final String KEY_PRODUCT = "product";
    public static final String KEY_CATEGORY = "category";
    public static final String KEY_SALE = "sale";
    public static final String KEY_RECEIPT = "receipt";
    
    // Request Codes
    public static final int REQUEST_ADD_PRODUCT = 1001;
    public static final int REQUEST_EDIT_PRODUCT = 1002;
    public static final int REQUEST_ADD_CATEGORY = 1003;
    public static final int REQUEST_EDIT_CATEGORY = 1004;
    
    // Preferences
    public static final String PREF_FILE_NAME = "smartstore_prefs";
    public static final String PREF_USER_ID = "user_id";
    public static final String PREF_USER_EMAIL = "user_email";
    
    // Default Values
    public static final int DEFAULT_LOW_STOCK_THRESHOLD = 10;
    public static final int DEFAULT_GRID_SPAN_COUNT = 2;
}
