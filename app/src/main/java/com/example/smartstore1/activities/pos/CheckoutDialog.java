package com.example.smartstore1.activities.pos;

import android.app.Dialog;
import android.content.Context;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.Window;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.TextView;
import androidx.annotation.NonNull;
import com.google.android.material.button.MaterialButton;
import com.google.android.material.textfield.TextInputEditText;
import com.google.android.material.textfield.TextInputLayout;
import com.google.firebase.database.DataSnapshot;
import com.example.smartstore1.R;
import com.example.smartstore1.database.firebase.CustomerRepository;
import com.example.smartstore1.database.firebase.FirebaseManager;
import com.example.smartstore1.models.CartItem;
import com.example.smartstore1.models.Customer;
import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class CheckoutDialog extends Dialog {
    private final List<CartItem> cartItems;
    private final double subtotal;
    private final double taxRate;
    private final double total;
    private OnCheckoutListener listener;
    
    private AutoCompleteTextView customerInput;
    private TextInputLayout amountLayout;
    private TextInputEditText amountInput;
    private TextView changeText;
    private MaterialButton confirmButton;
    
    private CustomerRepository customerRepository;

    public interface OnCheckoutListener {
        void onCheckout(String customerId, String paymentMethod, double amountPaid);
    }

    public CheckoutDialog(@NonNull Context context, List<CartItem> cartItems, 
                         double subtotal, double taxRate) {
        super(context);
        this.cartItems = cartItems;
        this.subtotal = subtotal;
        this.taxRate = taxRate;
        this.total = subtotal * (1 + taxRate);
        
        customerRepository = FirebaseManager.getInstance().getCustomerRepository();
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        setContentView(R.layout.dialog_checkout);

        initializeViews();
        setupCustomerInput();
        setupAmountInput();
        setupConfirmButton();
        displayTotals();
    }

    private void initializeViews() {
        customerInput = findViewById(R.id.customerInput);
        amountLayout = findViewById(R.id.amountLayout);
        amountInput = findViewById(R.id.amountInput);
        changeText = findViewById(R.id.changeText);
        confirmButton = findViewById(R.id.confirmButton);
    }

    private void setupCustomerInput() {
        customerRepository.getAllCustomers()
            .addOnSuccessListener(dataSnapshot -> {
                List<Customer> customers = new ArrayList<>();
                for (DataSnapshot snapshot : dataSnapshot.getChildren()) {
                    Customer customer = snapshot.getValue(Customer.class);
                    if (customer != null) {
                        customers.add(customer);
                    }
                }
                ArrayAdapter<Customer> adapter = new ArrayAdapter<>(
                    getContext(),
                    android.R.layout.simple_dropdown_item_1line,
                    customers
                );
                customerInput.setAdapter(adapter);
            });
    }

    private void setupAmountInput() {
        amountInput.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {}

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                calculateChange();
            }

            @Override
            public void afterTextChanged(Editable s) {}
        });
    }

    private void setupConfirmButton() {
        confirmButton.setOnClickListener(v -> {
            String customerId = ""; // Get selected customer ID
            String paymentMethod = "CASH"; // TODO: Add payment method selection
            double amountPaid = Double.parseDouble(amountInput.getText().toString());
            
            if (amountPaid < total) {
                amountLayout.setError("Amount is insufficient");
                return;
            }

            if (listener != null) {
                listener.onCheckout(customerId, paymentMethod, amountPaid);
            }
            dismiss();
        });
    }

    private void displayTotals() {
        NumberFormat currencyFormatter = NumberFormat.getCurrencyInstance(new Locale("en", "PH"));
        TextView subtotalText = findViewById(R.id.subtotalText);
        TextView taxText = findViewById(R.id.taxText);
        TextView totalText = findViewById(R.id.totalText);

        subtotalText.setText("Subtotal: " + currencyFormatter.format(subtotal));
        taxText.setText(String.format("Tax (%d%%): %s", 
            (int)(taxRate * 100), currencyFormatter.format(subtotal * taxRate)));
        totalText.setText("Total: " + currencyFormatter.format(total));
    }

    private void calculateChange() {
        try {
            double amountPaid = Double.parseDouble(amountInput.getText().toString());
            double change = amountPaid - total;
            
            NumberFormat currencyFormatter = NumberFormat.getCurrencyInstance(new Locale("en", "PH"));
            changeText.setText("Change: " + currencyFormatter.format(Math.max(0, change)));
            
            confirmButton.setEnabled(amountPaid >= total);
            amountLayout.setError(amountPaid < total ? "Amount is insufficient" : null);
        } catch (NumberFormatException e) {
            changeText.setText("Change: ₱0.00");
            confirmButton.setEnabled(false);
        }
    }

    public CheckoutDialog setOnCheckoutListener(OnCheckoutListener listener) {
        this.listener = listener;
        return this;
    }
}
