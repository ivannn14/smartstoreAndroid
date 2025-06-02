document.addEventListener('DOMContentLoaded', function() {
    let cart = [];
    const productItems = document.querySelectorAll('.product-item');
    
    // Category filter functionality
    document.querySelectorAll('.list-group-item').forEach(category => {
        category.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active state
            document.querySelectorAll('.list-group-item').forEach(item => {
                item.classList.remove('active');
            });
            this.classList.add('active');
            
            const selectedCategory = this.dataset.category;
            
            // Filter products
            productItems.forEach(item => {
                if (selectedCategory === 'all' || item.dataset.category === selectedCategory) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Search functionality
    const searchInput = document.getElementById('posSearch');
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        productItems.forEach(item => {
            const productName = item.querySelector('.product-name').textContent.toLowerCase();
            item.style.display = productName.includes(searchTerm) ? '' : 'none';
        });
    });

    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            const productName = this.dataset.name;
            const price = parseFloat(this.dataset.price);

            const existingItem = cart.find(item => item.id === productId);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    id: productId,
                    name: productName,
                    price: price,
                    quantity: 1
                });
            }
            updateCart();
        });
    });

    // Update cart display and calculations
    function updateCart() {
        const cartContainer = document.getElementById('cart-items');
        cartContainer.innerHTML = '';

        let subtotal = 0;

        cart.forEach(item => {
            subtotal += item.price * item.quantity;
            const itemElement = document.createElement('div');
            itemElement.className = 'cart-item';
            itemElement.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">${item.name}</h6>
                        <small class="text-muted">₱${item.price.toFixed(2)} x ${item.quantity}</small>
                    </div>
                    <div class="quantity-control">
                        <button class="quantity-btn minus" data-id="${item.id}">-</button>
                        <span>${item.quantity}</span>
                        <button class="quantity-btn plus" data-id="${item.id}">+</button>
                    </div>
                </div>
            `;
            cartContainer.appendChild(itemElement);
        });

        const tax = subtotal * 0.12;
        const total = subtotal + tax;

        document.getElementById('subtotal').textContent = `₱${subtotal.toFixed(2)}`;
        document.getElementById('tax').textContent = `₱${tax.toFixed(2)}`;
        document.getElementById('total').textContent = `₱${total.toFixed(2)}`;
    }

    // Handle quantity controls
    document.getElementById('cart-items').addEventListener('click', function(e) {
        if (e.target.classList.contains('quantity-btn')) {
            const productId = e.target.dataset.id;
            const item = cart.find(item => item.id === productId);
            
            if (e.target.classList.contains('plus')) {
                item.quantity += 1;
            } else if (e.target.classList.contains('minus')) {
                item.quantity -= 1;
                if (item.quantity === 0) {
                    cart = cart.filter(i => i.id !== productId);
                }
            }
            updateCart();
        }
    });

    // Clear cart
    document.getElementById('clear-cart-btn').addEventListener('click', function() {
        cart = [];
        updateCart();
    });

    // Handle cash amount input
    document.getElementById('cash-amount').addEventListener('input', function() {
        const total = parseFloat(document.getElementById('total').textContent.replace('₱', ''));
        const cashAmount = parseFloat(this.value) || 0;
        const change = cashAmount - total;
        document.getElementById('change-amount').textContent = `₱${Math.max(0, change).toFixed(2)}`;
    });

    // Checkout functionality
    document.getElementById('checkout-btn').addEventListener('click', function() {
        if (cart.length === 0) {
            alert('Please add items to cart before checking out');
            return;
        }

        const paymentMethod = document.getElementById('payment-method').value;
        const total = parseFloat(document.getElementById('total').textContent.replace('₱', ''));
        const cashAmount = parseFloat(document.getElementById('cash-amount').value) || 0;

        if (paymentMethod === 'cash' && cashAmount < total) {
            alert('Insufficient cash amount');
            return;
        }

        // Update stock
        fetch('/update-stock', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ items: cart })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                handleReceiptGeneration();
            } else {
                alert('Error updating stock. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error processing order');
        });
    });

    function handleReceiptGeneration() {
        const total = parseFloat(document.getElementById('total').textContent.replace('₱', ''));
        const paymentMethod = document.getElementById('payment-method').value;
        const cashAmount = parseFloat(document.getElementById('cash-amount').value) || 0;

        const order = {
            items: cart,
            total: total,
            paymentMethod: paymentMethod,
            cashAmount: paymentMethod === 'cash' ? cashAmount : null,
            subtotal: parseFloat(document.getElementById('subtotal').textContent.replace('₱', '')),
            tax: parseFloat(document.getElementById('tax').textContent.replace('₱', ''))
        };

        fetch('/generate-receipt', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(order)
        })
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'receipt.pdf';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            
            resetCart();
        });
    }

    function resetCart() {
        cart = [];
        updateCart();
        document.getElementById('cash-amount').value = '';
        document.getElementById('change-amount').textContent = '₱0.00';
        window.location.reload();
    }
});