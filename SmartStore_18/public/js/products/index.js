document.addEventListener('DOMContentLoaded', function() {
    // Low Stock Alert Modal
    window.showLowStockAlert = function() {
        const lowStockProducts = JSON.parse(document.getElementById('low-stock-data').textContent);
        
        let modalContent = `
            <div class="modal fade" id="lowStockModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Low Stock Alerts</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
            `;

        if (lowStockProducts.length > 0) {
            lowStockProducts.forEach(product => {
                modalContent += `
                    <div class="low-stock-alert">
                        <div>
                            <h6 class="mb-1">${product.name}</h6>
                            <small class="text-muted">SKU: ${product.sku}</small>
                        </div>
                        <div class="low-stock-count">
                            ${product.stock_quantity} left
                        </div>
                    </div>
                `;
            });
        } else {
            modalContent += `
                <div class="text-center py-4">
                    <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                    <h5>All Stock Levels are Good</h5>
                    <p class="text-muted">No products are running low on stock</p>
                </div>
            `;
        }

        modalContent += `
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if any
        const existingModal = document.getElementById('lowStockModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Add new modal to body
        document.body.insertAdjacentHTML('beforeend', modalContent);

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('lowStockModal'));
        modal.show();
    };
});