package com.example.smartstore1.adapters;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.example.smartstore1.R;
import com.example.smartstore1.models.Product;
import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class POSProductAdapter extends RecyclerView.Adapter<POSProductAdapter.ViewHolder> {
    private List<Product> products;
    private final ProductClickListener listener;

    public interface ProductClickListener {
        void onProductClick(Product product);
    }

    public POSProductAdapter(List<Product> products, ProductClickListener listener) {
        this.products = products;
        this.listener = listener;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.item_pos_product, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        Product product = products.get(position);
        holder.bind(product, listener);
    }

    @Override
    public int getItemCount() {
        return products.size();
    }

    public void updateData(List<Product> newProducts) {
        this.products = newProducts;
        notifyDataSetChanged();
    }

    static class ViewHolder extends RecyclerView.ViewHolder {
        private final ImageView productImage;
        private final TextView productName;
        private final TextView productPrice;
        private final TextView stockQuantity;

        ViewHolder(View itemView) {
            super(itemView);
            productImage = itemView.findViewById(R.id.productImage);
            productName = itemView.findViewById(R.id.productName);
            productPrice = itemView.findViewById(R.id.productPrice);
            stockQuantity = itemView.findViewById(R.id.stockQuantity);
        }

        void bind(final Product product, final ProductClickListener listener) {
            productName.setText(product.getName());
            NumberFormat currencyFormatter = NumberFormat.getCurrencyInstance(new Locale("en", "PH"));
            productPrice.setText(currencyFormatter.format(product.getPrice()));
            stockQuantity.setText("Stock: " + product.getStock());

            // Set click listener
            itemView.setOnClickListener(v -> {
                if (product.getStock() > 0) {
                    listener.onProductClick(product);
                }
            });

            // Disable item if out of stock
            itemView.setEnabled(product.getStock() > 0);
            itemView.setAlpha(product.getStock() > 0 ? 1f : 0.5f);
        }
    }
}
