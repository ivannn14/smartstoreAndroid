package com.example.smartstore1.adapters;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.example.smartstore1.R;
import com.example.smartstore1.models.Sale;
import java.text.NumberFormat;
import java.text.SimpleDateFormat;
import java.util.List;
import java.util.Locale;

public class TransactionAdapter extends RecyclerView.Adapter<TransactionAdapter.ViewHolder> {
    private final List<Sale> transactions;
    private final OnTransactionClickListener listener;
    private final NumberFormat currencyFormatter;
    private final SimpleDateFormat dateFormatter;

    public interface OnTransactionClickListener {
        void onTransactionClick(Sale sale);
    }

    public TransactionAdapter(List<Sale> transactions, OnTransactionClickListener listener) {
        this.transactions = transactions;
        this.listener = listener;
        this.currencyFormatter = NumberFormat.getCurrencyInstance(new Locale("en", "PH"));
        this.dateFormatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault());
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext())
            .inflate(R.layout.item_transaction, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        Sale sale = transactions.get(position);
        holder.saleIdText.setText("Sale #: " + sale.getId());
        holder.dateText.setText(dateFormatter.format(sale.getDate()));
        holder.totalText.setText(currencyFormatter.format(sale.getTotal()));
        holder.itemView.setOnClickListener(v -> listener.onTransactionClick(sale));
    }

    @Override
    public int getItemCount() {
        return transactions.size();
    }

    static class ViewHolder extends RecyclerView.ViewHolder {
        TextView saleIdText;
        TextView dateText;
        TextView totalText;

        ViewHolder(View view) {
            super(view);
            saleIdText = view.findViewById(R.id.saleIdText);
            dateText = view.findViewById(R.id.dateText);
            totalText = view.findViewById(R.id.totalText);
        }
    }
}
