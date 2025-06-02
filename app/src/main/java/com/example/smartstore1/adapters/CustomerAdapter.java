package com.example.smartstore1.adapters;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageButton;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.example.smartstore1.R;
import com.example.smartstore1.models.Customer;
import java.util.List;
import java.util.Locale;

public class CustomerAdapter extends RecyclerView.Adapter<CustomerAdapter.ViewHolder> {
    private List<Customer> customers;
    private final CustomerClickListener listener;

    public interface CustomerClickListener {
        void onCustomerClick(Customer customer);
        void onEditClick(Customer customer);
        void onDeleteClick(Customer customer);
    }

    public CustomerAdapter(List<Customer> customers, CustomerClickListener listener) {
        this.customers = customers;
        this.listener = listener;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.item_customer, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        Customer customer = customers.get(position);
        holder.bind(customer, listener);
    }

    @Override
    public int getItemCount() {
        return customers.size();
    }

    public void updateData(List<Customer> newCustomers) {
        this.customers = newCustomers;
        notifyDataSetChanged();
    }

    static class ViewHolder extends RecyclerView.ViewHolder {
        private final TextView customerName;
        private final TextView customerEmail;
        private final TextView customerPhone;
        private final TextView totalPurchases;
        private final ImageButton editButton;
        private final ImageButton deleteButton;

        ViewHolder(View itemView) {
            super(itemView);
            customerName = itemView.findViewById(R.id.customerName);
            customerEmail = itemView.findViewById(R.id.customerEmail);
            customerPhone = itemView.findViewById(R.id.customerPhone);
            totalPurchases = itemView.findViewById(R.id.totalPurchases);
            editButton = itemView.findViewById(R.id.editButton);
            deleteButton = itemView.findViewById(R.id.deleteButton);
        }

        void bind(final Customer customer, final CustomerClickListener listener) {
            customerName.setText(customer.getName());
            customerEmail.setText(customer.getEmail());
            customerPhone.setText(customer.getPhone());
            totalPurchases.setText(String.format(Locale.getDefault(), 
                "Total Purchases: $%.2f", customer.getTotalPurchases()));

            itemView.setOnClickListener(v -> listener.onCustomerClick(customer));
            editButton.setOnClickListener(v -> listener.onEditClick(customer));
            deleteButton.setOnClickListener(v -> listener.onDeleteClick(customer));
        }
    }
}
