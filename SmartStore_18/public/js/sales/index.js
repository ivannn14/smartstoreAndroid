document.addEventListener('DOMContentLoaded', function() {
    initializeSearch();
    initializeDateFilter();
});

function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', debounce(handleSearch, 300));
    }
}

function initializeDateFilter() {
    const dateFilter = document.getElementById('dateFilter');
    if (dateFilter) {
        dateFilter.addEventListener('change', handleDateFilter);
    }
}

function handleSearch(e) {
    const searchText = e.target.value.toLowerCase();
    filterTableRows(row => {
        const text = row.textContent.toLowerCase();
        return text.includes(searchText);
    });
}

function handleDateFilter(e) {
    const filter = e.target.value;
    filterTableRows(row => {
        const dateCell = row.querySelector('td:nth-child(6)');
        if (!dateCell) return true;
        
        const date = new Date(dateCell.textContent);
        
        switch(filter) {
            case 'today': return isToday(date);
            case 'week': return isThisWeek(date);
            case 'month': return isThisMonth(date);
            default: return true;
        }
    });
}

function filterTableRows(filterFn) {
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.style.display = filterFn(row) ? '' : 'none';
    });
}

function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

function isToday(date) {
    const today = new Date();
    return date.toDateString() === today.toDateString();
}

function isThisWeek(date) {
    const today = new Date();
    const firstDay = new Date(today.setDate(today.getDate() - today.getDay()));
    const lastDay = new Date(today.setDate(today.getDate() - today.getDay() + 6));
    return date >= firstDay && date <= lastDay;
}

function isThisMonth(date) {
    const today = new Date();
    return date.getMonth() === today.getMonth() && 
           date.getFullYear() === today.getFullYear();
}

function deleteSale(saleId) {
    if (!confirm('Are you sure you want to delete this sale?')) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/sales/${saleId}`;
    form.innerHTML = `
        @csrf
        @method('DELETE')
    `;
    document.body.appendChild(form);
    form.submit();
}

function exportToExcel() {
    window.location.href = document.querySelector('meta[name="export-url"]').content;
}

function printReceipt() {
    const receiptUrl = document.querySelector('meta[name="receipt-url"]').content;
    const receiptWindow = window.open(receiptUrl, '_blank', 'width=400,height=600');
    receiptWindow.focus();
    receiptWindow.print();
}

// Barcode Scanner
class BarcodeScanner {
    constructor() {
        this.isScanning = false;
        this.stream = null;
    }

    async scan() {
        if (!('BarcodeDetector' in window)) {
            alert('Barcode scanning is not supported in this browser. Please use manual entry.');
            return;
        }

        try {
            this.stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: "environment" }
            });

            const video = document.createElement('video');
            video.srcObject = this.stream;
            await video.play();

            const barcodeDetector = new BarcodeDetector();
            this.isScanning = true;

            while (this.isScanning) {
                const barcodes = await barcodeDetector.detect(video);
                if (barcodes.length > 0) {
                    await this.handleBarcode(barcodes[0].rawValue);
                    break;
                }
                await new Promise(resolve => setTimeout(resolve, 100));
            }
        } catch (error) {
            console.error('Scanner error:', error);
            alert('Unable to access camera. Please use manual entry.');
        } finally {
            this.stopScanning();
        }
    }

    stopScanning() {
        this.isScanning = false;
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
        }
    }

    async handleBarcode(barcode) {
        try {
            const response = await fetch(`/api/products/barcode/${barcode}`);
            const product = await response.json();

            if (!product) {
                alert('Product not found');
                return;
            }

            await this.addToSale(product);
        } catch (error) {
            console.error('Barcode handling error:', error);
            alert('Error processing barcode');
        }
    }

    async addToSale(product) {
        try {
            const response = await fetch(`/api/products/${product.id}/stock`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ quantity: 1 })
            });

            const data = await response.json();
            if (data.success) {
                window.location.reload();
            } else {
                alert('Error updating stock');
            }
        } catch (error) {
            console.error('Sale update error:', error);
            alert('Error updating sale');
        }
    }
}

const barcodeScanner = new BarcodeScanner();

function scanBarcode() {
    barcodeScanner.scan();
}