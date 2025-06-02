package com.example.smartstore1.adapters;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.example.smartstore1.R;
import com.example.smartstore1.models.Sale;
import java.text.SimpleDateFormat;
import java.util.List;
import java.util.Locale;

public class SaleAdapter extends RecyclerView.Adapter<SaleAdapter.ViewHolder> {
    private List<Sale> sales;
    private final SaleClickListener listener;
    private final SimpleDateFormat dateFormat;

    public interface SaleClickListener {
        void onSaleClick(Sale sale);
        void onDeleteClick(Sale sale);
    }

    public SaleAdapter(List<Sale> sales, SaleClickListener listener) {
        this.sales = sales;
        this.listener = listener;
        this.dateFormat = new SimpleDateFormat("dd/MM/yyyy HH:mm", Locale.getDefault());
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.item_sale, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        Sale sale = sales.get(position);
        holder.bind(sale, listener, dateFormat);
    }

    @Override
    public int getItemCount() {
        return sales.size();
    }

    public void updateSales(List<Sale> sales) {
        this.sales = sales;
        notifyDataSetChanged();
    }

    // Alias for updateSales to maintain compatibility
    public void updateData(List<Sale> sales) {
        updateSales(sales);
    }

    static class ViewHolder extends RecyclerView.ViewHolder {
        private final TextView saleIdText;
        private final TextView saleDateText;
        private final TextView totalAmountText;
        private final TextView paymentMethodText;

        ViewHolder(View itemView) {
            super(itemView);
            saleIdText = itemView.findViewById(R.id.saleIdText);
            saleDateText = itemView.findViewById(R.id.saleDateText);
            totalAmountText = itemView.findViewById(R.id.totalAmountText);
            paymentMethodText = itemView.findViewById(R.id.paymentMethodText);
        }

        void bind(final Sale sale, final SaleClickListener listener, SimpleDateFormat dateFormat) {
            saleIdText.setText(String.format("Sale #%s", sale.getId()));
            saleDateText.setText(dateFormat.format(sale.getDate()));
            totalAmountText.setText(String.format(Locale.getDefault(), "₱%.2f", sale.getTotal()));
            paymentMethodText.setText(sale.getPaymentMethod());
            itemView.setOnClickListener(v -> listener.onSaleClick(sale));
            itemView.findViewById(R.id.deleteButton).setOnClickListener(v -> listener.onDeleteClick(sale));
        }
    }
}
