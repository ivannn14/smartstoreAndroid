package com.example.smartstore1.activities.transactions;

import android.os.Bundle;
import android.view.MenuItem;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import com.example.smartstore1.R;
import com.example.smartstore1.adapters.TransactionAdapter;
import com.example.smartstore1.activities.pos.ReceiptDialog;
import com.example.smartstore1.database.firebase.FirebaseManager;
import com.example.smartstore1.models.Receipt;
import com.example.smartstore1.models.Sale;
import com.google.firebase.database.DataSnapshot;
import java.util.ArrayList;
import java.util.List;

public class TransactionHistoryActivity extends AppCompatActivity {
    private RecyclerView transactionsRecyclerView;
    private TransactionAdapter adapter;
    private List<Sale> transactions = new ArrayList<>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_transaction_history);

        // Enable back button in action bar
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setTitle("Transaction History");
        }

        initializeViews();
        setupRecyclerView();
        loadTransactions();
    }

    private void initializeViews() {
        transactionsRecyclerView = findViewById(R.id.transactionsRecyclerView);
    }

    private void setupRecyclerView() {
        adapter = new TransactionAdapter(transactions, this::showReceiptDialog);
        transactionsRecyclerView.setLayoutManager(new LinearLayoutManager(this));
        transactionsRecyclerView.setAdapter(adapter);
    }

    private List<Sale> convertDataSnapshotToSales(DataSnapshot dataSnapshot) {
        List<Sale> sales = new ArrayList<>();
        for (DataSnapshot snapshot : dataSnapshot.getChildren()) {
            Sale sale = snapshot.getValue(Sale.class);
            if (sale != null) {
                sales.add(sale);
            }
        }
        return sales;
    }

    private void loadTransactions() {
        FirebaseManager.getInstance().getSaleRepository()
            .getAllSales()
            .addOnSuccessListener(dataSnapshot -> {
                transactions.clear();
                transactions.addAll(convertDataSnapshotToSales(dataSnapshot));
                adapter.notifyDataSetChanged();
            })
            .addOnFailureListener(e -> 
                Toast.makeText(this, "Failed to load transactions", Toast.LENGTH_SHORT).show());
    }

    private void showReceiptDialog(Sale sale) {
        FirebaseManager.getInstance().getReceiptRepository()
            .getReceiptBySaleId(sale.getId())
            .addOnSuccessListener(dataSnapshot -> {
                Receipt receipt = dataSnapshot.getValue(Receipt.class);
                if (receipt != null) {
                    new ReceiptDialog(this, receipt).show();
                } else {
                    Toast.makeText(this, "Receipt not found", Toast.LENGTH_SHORT).show();
                }
            })
            .addOnFailureListener(e -> 
                Toast.makeText(this, "Failed to load receipt", Toast.LENGTH_SHORT).show());
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        if (item.getItemId() == android.R.id.home) {
            onBackPressed();
            return true;
        }
        return super.onOptionsItemSelected(item);
    }
}
