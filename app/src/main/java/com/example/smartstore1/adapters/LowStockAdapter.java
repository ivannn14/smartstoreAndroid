package com.example.smartstore1.adapters;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.example.smartstore1.R;
import com.example.smartstore1.models.Product;
import java.util.List;

public class LowStockAdapter extends RecyclerView.Adapter<LowStockAdapter.ViewHolder> {
    private List<Product> products;
    
    public LowStockAdapter(List<Product> products) {
        this.products = products;
    }
    
    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext())
            .inflate(R.layout.item_low_stock, parent, false);
        return new ViewHolder(view);
    }
    
    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        Product product = products.get(position);
        holder.productName.setText(product.getName());
        holder.stockLevel.setText("Stock: " + product.getStock());
    }
    
    @Override
    public int getItemCount() {
        return products.size();
    }
    
    static class ViewHolder extends RecyclerView.ViewHolder {
        TextView productName;
        TextView stockLevel;
        
        ViewHolder(View view) {
            super(view);
            productName = view.findViewById(R.id.product_name);
            stockLevel = view.findViewById(R.id.stock_level);
        }
    }
    
    public void updateProducts(List<Product> products) {
        this.products = products;
        notifyDataSetChanged();
    }
}