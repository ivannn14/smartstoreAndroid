document.addEventListener('DOMContentLoaded', function() {
    initializeSearchAndFilter();
});

function initializeSearchAndFilter() {
    const searchInput = document.getElementById('searchExpense');
    const categoryFilter = document.getElementById('categoryFilter');
    
    searchInput.addEventListener('input', debounce(function(e) {
        filterExpenses();
    }, 300));

    categoryFilter.addEventListener('change', function() {
        filterExpenses();
    });
}

function filterExpenses() {
    const searchInput = document.getElementById('searchExpense');
    const categoryFilter = document.getElementById('categoryFilter');
    const searchTerm = searchInput.value.toLowerCase();
    const category = categoryFilter.value;
    
    const baseUrl = document.querySelector('meta[name="app-url"]').getAttribute('content');
    window.location.href = `${baseUrl}/expenses?search=${searchTerm}&category=${category}`;
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function deleteExpense(id) {
    if (confirm('Are you sure you want to delete this expense?')) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/expenses/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token
            }
        })
        .then(response => {
            if (response.ok) {
                showNotification('Expense deleted successfully', 'success');
                location.reload();
            } else {
                showNotification('Error deleting expense', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error deleting expense', 'error');
        });
    }
}

function showNotification(message, type) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}