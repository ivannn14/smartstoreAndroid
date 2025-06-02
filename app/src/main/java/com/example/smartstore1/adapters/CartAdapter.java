package com.example.smartstore1.adapters;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageButton;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.example.smartstore1.R;
import com.example.smartstore1.models.CartItem;
import java.util.List;
import java.util.Locale;

public class CartAdapter extends RecyclerView.Adapter<CartAdapter.ViewHolder> {
    private List<CartItem> cartItems;
    private final CartItemClickListener listener;

    public interface CartItemClickListener {
        void onIncrementClick(CartItem item, int position);
        void onDecrementClick(CartItem item, int position);
        void onRemoveClick(CartItem item, int position);
    }

    public CartAdapter(List<CartItem> cartItems, CartItemClickListener listener) {
        this.cartItems = cartItems;
        this.listener = listener;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.item_cart, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        CartItem item = cartItems.get(position);
        holder.bind(item, listener, position);
    }

    @Override
    public int getItemCount() {
        return cartItems.size();
    }

    public void updateData(List<CartItem> newCartItems) {
        this.cartItems = newCartItems;
        notifyDataSetChanged();
    }

    static class ViewHolder extends RecyclerView.ViewHolder {
        private final TextView cartItemName;
        private final TextView cartItemPrice;
        private final TextView quantityText;
        private final ImageButton incrementButton;
        private final ImageButton decrementButton;
        private final ImageButton removeButton;

        ViewHolder(View itemView) {
            super(itemView);
            cartItemName = itemView.findViewById(R.id.cartItemName);
            cartItemPrice = itemView.findViewById(R.id.cartItemPrice);
            quantityText = itemView.findViewById(R.id.quantityText);
            incrementButton = itemView.findViewById(R.id.incrementButton);
            decrementButton = itemView.findViewById(R.id.decrementButton);
            removeButton = itemView.findViewById(R.id.removeButton);
        }

        void bind(final CartItem item, final CartItemClickListener listener, final int position) {
            cartItemName.setText(item.getProduct().getName());
            cartItemPrice.setText(String.format(Locale.getDefault(), "₱%.2f", 
                item.getProduct().getPrice() * item.getQuantity()));
            quantityText.setText(String.valueOf(item.getQuantity()));

            incrementButton.setOnClickListener(v -> listener.onIncrementClick(item, position));
            decrementButton.setOnClickListener(v -> listener.onDecrementClick(item, position));
            removeButton.setOnClickListener(v -> listener.onRemoveClick(item, position));

            // Disable decrement button if quantity is 1
            decrementButton.setEnabled(item.getQuantity() > 1);
            decrementButton.setAlpha(item.getQuantity() > 1 ? 1f : 0.5f);
        }
    }
}
