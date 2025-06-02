package com.example.smartstore1.activities.pos;

import android.content.Intent;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import com.google.android.material.button.MaterialButton;
import com.google.android.material.chip.Chip;
import com.google.android.material.chip.ChipGroup;
import com.google.android.material.textfield.TextInputEditText;
import com.example.smartstore1.R;
import com.example.smartstore1.activities.transactions.TransactionHistoryActivity;
import com.example.smartstore1.adapters.CartAdapter;
import com.example.smartstore1.adapters.POSProductAdapter;
import com.example.smartstore1.database.firebase.CategoryRepository;
import com.example.smartstore1.database.firebase.FirebaseManager;
import com.example.smartstore1.database.firebase.ProductRepository;
import com.example.smartstore1.models.CartItem;
import com.example.smartstore1.models.Category;
import com.example.smartstore1.models.Product;
import com.example.smartstore1.models.Receipt;
import com.example.smartstore1.models.Sale;
import com.example.smartstore1.models.SaleItem;
import com.example.smartstore1.utils.AppConstants;

import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Locale;
import java.util.UUID;
import java.util.concurrent.atomic.AtomicInteger;
import java.util.stream.Collectors;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.database.DataSnapshot;

public class POSActivity extends AppCompatActivity implements POSProductAdapter.ProductClickListener, CartAdapter.CartItemClickListener {
    private RecyclerView productsRecyclerView;
    private RecyclerView cartRecyclerView;
    private ChipGroup categoryChipGroup;
    private TextInputEditText searchInput;
    private TextView subtotalText;
    private TextView taxText;
    private TextView totalText;
    private MaterialButton checkoutButton;
    
    private POSProductAdapter productAdapter;
    private CartAdapter cartAdapter;
    private ProductRepository productRepository;
    private CategoryRepository categoryRepository;
    
    private List<Product> allProducts = new ArrayList<>();
    private List<CartItem> cartItems = new ArrayList<>();
    private static final double TAX_RATE = 0.12; // 12% tax rate

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_pos);
        
        FirebaseManager firebaseManager = FirebaseManager.getInstance();
        productRepository = firebaseManager.getProductRepository();
        categoryRepository = firebaseManager.getCategoryRepository();
        
        initializeViews();
        setupRecyclerViews();
        setupListeners();
        loadData();
    }

    private void initializeViews() {
        productsRecyclerView = findViewById(R.id.productsRecyclerView);
        cartRecyclerView = findViewById(R.id.cartRecyclerView);
        categoryChipGroup = findViewById(R.id.categoryChipGroup);
        searchInput = findViewById(R.id.searchInput);
        subtotalText = findViewById(R.id.subtotalText);
        taxText = findViewById(R.id.taxText);
        totalText = findViewById(R.id.totalText);
        checkoutButton = findViewById(R.id.checkoutButton);
    }

    private void setupRecyclerViews() {
        // Products grid
        productAdapter = new POSProductAdapter(new ArrayList<>(), this);
        productsRecyclerView.setLayoutManager(new GridLayoutManager(this, 2));
        productsRecyclerView.setAdapter(productAdapter);

        // Cart list
        cartAdapter = new CartAdapter(cartItems, this);
        cartRecyclerView.setLayoutManager(new LinearLayoutManager(this));
        cartRecyclerView.setAdapter(cartAdapter);
    }

    private void setupListeners() {
        searchInput.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {}

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                filterProducts(s.toString());
            }

            @Override
            public void afterTextChanged(Editable s) {}
        });

        categoryChipGroup.setOnCheckedChangeListener((group, checkedId) -> {
            if (checkedId == View.NO_ID) {
                productAdapter.updateData(allProducts);
            } else {
                Chip chip = group.findViewById(checkedId);
                filterProductsByCategory(chip.getText().toString());
            }
        });

        checkoutButton.setOnClickListener(v -> {
            if (cartItems.isEmpty()) {
                Toast.makeText(this, "Cart is empty", Toast.LENGTH_SHORT).show();
                return;
            }
            showCheckoutDialog();
        });
    }

    private void loadData() {
        // Load categories
        categoryRepository.getAllCategories()
            .addOnSuccessListener(categories -> {
                for (DataSnapshot categorySnapshot : categories.getChildren()) {
                    Category category = categorySnapshot.getValue(Category.class);
                    if (category != null) {
                        addCategoryChip(category);
                    }
                }
            })
            .addOnFailureListener(e -> 
                Toast.makeText(this, "Failed to load categories", Toast.LENGTH_SHORT).show());        // Load products
        productRepository.getAllProducts()
            .addOnSuccessListener(dataSnapshot -> {
                List<Product> products = convertDataSnapshotToProducts(dataSnapshot);
                allProducts = products;
                productAdapter.updateData(products);
            })
            .addOnFailureListener(e -> 
                Toast.makeText(this, "Failed to load products", Toast.LENGTH_SHORT).show());
    }

    private void addCategoryChip(Category category) {
        Chip chip = new Chip(this);
        chip.setText(category.getName());
        chip.setCheckable(true);
        categoryChipGroup.addView(chip);
    }

    private void filterProducts(String query) {
        if (query.isEmpty()) {
            productAdapter.updateData(allProducts);
            return;
        }

        List<Product> filteredList = allProducts.stream()
            .filter(product -> 
                product.getName().toLowerCase().contains(query.toLowerCase()) ||
                product.getDescription().toLowerCase().contains(query.toLowerCase()))
            .collect(Collectors.toList());
        
        productAdapter.updateData(filteredList);
    }

    private void filterProductsByCategory(String category) {
        List<Product> filteredList = allProducts.stream()
            .filter(product -> product.getCategory().equals(category))
            .collect(Collectors.toList());
        
        productAdapter.updateData(filteredList);
    }

    @Override
    public void onProductClick(Product product) {
        // Add to cart
        CartItem existingItem = findCartItem(product.getId());
        if (existingItem != null) {
            existingItem.setQuantity(existingItem.getQuantity() + 1);
            cartAdapter.notifyDataSetChanged();
        } else {
            CartItem newItem = new CartItem(product, 1);
            cartItems.add(newItem);
            cartAdapter.updateData(cartItems);
        }
        updateTotals();
    }

    @Override
    public void onIncrementClick(CartItem item, int position) {
        item.setQuantity(item.getQuantity() + 1);
        cartAdapter.notifyItemChanged(position);
        updateTotals();
    }

    @Override
    public void onDecrementClick(CartItem item, int position) {
        if (item.getQuantity() > 1) {
            item.setQuantity(item.getQuantity() - 1);
            cartAdapter.notifyItemChanged(position);
        } else {
            cartItems.remove(position);
            cartAdapter.notifyItemRemoved(position);
        }
        updateTotals();
    }

    @Override
    public void onRemoveClick(CartItem item, int position) {
        cartItems.remove(position);
        cartAdapter.notifyItemRemoved(position);
        updateTotals();
    }

    private CartItem findCartItem(String productId) {
        return cartItems.stream()
            .filter(item -> item.getProduct().getId().equals(productId))
            .findFirst()
            .orElse(null);
    }

    private void updateTotals() {
        double subtotal = calculateSubtotal();
        double tax = subtotal * TAX_RATE;
        double total = subtotal + tax;

        NumberFormat currencyFormatter = NumberFormat.getCurrencyInstance(new Locale("en", "PH"));
        subtotalText.setText("Subtotal: " + currencyFormatter.format(subtotal));
        taxText.setText("Tax (12%): " + currencyFormatter.format(tax));
        totalText.setText("Total: " + currencyFormatter.format(total));

        checkoutButton.setEnabled(!cartItems.isEmpty());
    }

    private double calculateSubtotal() {
        return cartItems.stream()
            .mapToDouble(item -> item.getProduct().getPrice() * item.getQuantity())
            .sum();
    }

    private void showCheckoutDialog() {
        // TODO: Implement checkout dialog
        new CheckoutDialog(this, cartItems, calculateSubtotal(), TAX_RATE)
            .setOnCheckoutListener(this::processCheckout)
            .show();
    }

    private void processCheckout(String customerId, String paymentMethod, double amountPaid) {
        // Create sale record
        Sale sale = new Sale();
        sale.setId(UUID.randomUUID().toString());
        sale.setDate(new Date());
        sale.setCustomerId(customerId);
        sale.setTotal(calculateSubtotal() * (1 + TAX_RATE));
        sale.setCost(calculateCost());
        sale.setItems(convertCartItemsToSaleItems(cartItems));

        // Create receipt
        Receipt receipt = new Receipt();
        receipt.setId(UUID.randomUUID().toString());
        receipt.setSaleId(sale.getId());
        receipt.setDate(sale.getDate());
        receipt.setCustomerName(getCustomerName(customerId));
        receipt.setItems(convertCartItemsToSaleItems(cartItems));
        receipt.setSubtotal(calculateSubtotal());
        receipt.setTax(calculateSubtotal() * TAX_RATE);
        receipt.setTotal(sale.getTotal());
        receipt.setAmountPaid(amountPaid);
        receipt.setChange(amountPaid - sale.getTotal());
        receipt.setPaymentMethod(paymentMethod);
        receipt.setCashierName(FirebaseAuth.getInstance().getCurrentUser().getDisplayName());

        // Update inventory and save sale
        updateInventory(sale, () -> {
            saveSaleAndReceipt(sale, receipt);
        });
    }

    private void updateInventory(Sale sale, Runnable onComplete) {
        AtomicInteger updatesRemaining = new AtomicInteger(sale.getItems().size());

        for (SaleItem item : sale.getItems()) {
            Product product = item.getProduct();
            product.setStock(product.getStock() - item.getQuantity());

            productRepository.updateProduct(product)
                .addOnSuccessListener(aVoid -> {
                    if (updatesRemaining.decrementAndGet() == 0) {
                        onComplete.run();
                    }
                })
                .addOnFailureListener(e -> {
                    Toast.makeText(this, "Failed to update inventory", Toast.LENGTH_SHORT).show();
                });
        }
    }

    private void saveSaleAndReceipt(Sale sale, Receipt receipt) {
        FirebaseManager.getInstance().getSaleRepository()
            .addSale(sale)
            .addOnSuccessListener(aVoid -> {
                FirebaseManager.getInstance().getReceiptRepository()
                    .addReceipt(receipt)
                    .addOnSuccessListener(aVoid2 -> {
                        showReceiptDialog(receipt);
                        clearCart();
                    })
                    .addOnFailureListener(e -> 
                        Toast.makeText(this, "Failed to save receipt", Toast.LENGTH_SHORT).show());
            })
            .addOnFailureListener(e -> 
                Toast.makeText(this, "Failed to save sale", Toast.LENGTH_SHORT).show());
    }

    private double calculateCost() {
        return cartItems.stream()
            .mapToDouble(item -> item.getProduct().getCost() * item.getQuantity())
            .sum();
    }

    private String getCustomerName(String customerId) {
        // TODO: Get customer name from repository
        return "Walk-in Customer";
    }

    private void showReceiptDialog(Receipt receipt) {
        new ReceiptDialog(this, receipt).show();
    }

    private void clearCart() {
        cartItems.clear();
        cartAdapter.notifyDataSetChanged();
        updateTotals();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_pos, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        if (item.getItemId() == R.id.menu_history) {
            startActivity(new Intent(this, TransactionHistoryActivity.class));
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    private List<SaleItem> convertCartItemsToSaleItems(List<CartItem> cartItems) {
        return cartItems.stream()
            .map(cartItem -> new SaleItem(
                cartItem.getProduct().getId(),
                cartItem.getProduct().getName(),
                cartItem.getQuantity(),
                cartItem.getProduct().getPrice(),
                cartItem.getProduct().getCost()
            ))
            .collect(Collectors.toList());
    }

    private List<Product> convertDataSnapshotToProducts(DataSnapshot dataSnapshot) {
        List<Product> products = new ArrayList<>();
        for (DataSnapshot snapshot : dataSnapshot.getChildren()) {
            Product product = snapshot.getValue(Product.class);
            if (product != null) {
                products.add(product);
            }
        }
        return products;
    }

    private void loadProducts() {
        productRepository.getAllProducts()
            .addOnSuccessListener(dataSnapshot -> {
                List<Product> products = convertDataSnapshotToProducts(dataSnapshot);
                allProducts = products;
                productAdapter.updateData(products);
                updateTotals();
            })
            .addOnFailureListener(e -> {
                Toast.makeText(this, "Failed to load products", Toast.LENGTH_SHORT).show();
            });
    }
}
