document.addEventListener('DOMContentLoaded', function() {
    initializeSalesChart();
    initializeDateFilter();
    initializeButtons();
});

function initializeSalesChart() {
    const ctx = document.getElementById("salesChart").getContext("2d");
    const salesData = JSON.parse(document.getElementById('sales-data').textContent);
    
    new Chart(ctx, {
        type: "line",
        data: {
            labels: salesData.months,
            datasets: [{
                label: "Sales",
                data: salesData.totals,
                tension: 0.4,
                borderWidth: 2,
                borderColor: "#5e72e4",
                backgroundColor: "rgba(94, 114, 228, 0.1)",
                fill: true,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointBackgroundColor: "#5e72e4",
                pointBorderColor: "#fff",
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: "rgba(255, 255, 255, 0.9)",
                    titleColor: "#000",
                    bodyColor: "#000",
                    borderColor: "#e9ecef",
                    borderWidth: 1,
                    padding: 10,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return '₱' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            hover: {
                mode: 'nearest',
                intersect: false
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        borderDash: [5, 5],
                        color: "rgba(0, 0, 0, 0.05)"
                    },
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        },
                        color: "#67748e"
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: "#67748e"
                    }
                }
            }
        }
    });
}

function initializeDateFilter() {
    const dateFilter = document.querySelector('.form-select');
    if (dateFilter) {
        dateFilter.addEventListener('change', function(e) {
            updateChartData(e.target.value);
        });
    }
}

function initializeButtons() {
    const addSaleBtn = document.querySelector('.btn-primary');
    const scanBtn = document.querySelector('.btn-warning');

    if (addSaleBtn) {
        addSaleBtn.addEventListener('click', function() {
            window.location.href = '/pos';
        });
    }

    if (scanBtn) {
        scanBtn.addEventListener('click', function() {
            initializeScanner();
        });
    }
}

async function updateChartData(days) {
    try {
        const response = await fetch(`/api/sales/chart-data/${days}`);
        const data = await response.json();
        
        const chart = Chart.getChart("salesChart");
        chart.data.labels = data.months;
        chart.data.datasets[0].data = data.totals;
        chart.update();
    } catch (error) {
        console.error('Error updating chart:', error);
        showNotification('Error updating chart data', 'error');
    }
}

function showNotification(message, type) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    toast.style.zIndex = '1050';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

async function initializeScanner() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
        const video = document.createElement('video');
        video.srcObject = stream;
        await video.play();

        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');

        const barcodeDetector = new BarcodeDetector();
        
        const scanInterval = setInterval(async () => {
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            try {
                const barcodes = await barcodeDetector.detect(canvas);
                if (barcodes.length > 0) {
                    clearInterval(scanInterval);
                    stream.getTracks().forEach(track => track.stop());
                    handleBarcode(barcodes[0].rawValue);
                }
            } catch (error) {
                console.error('Barcode detection error:', error);
            }
        }, 100);

    } catch (error) {
        console.error('Scanner error:', error);
        showNotification('Unable to access camera', 'error');
    }
}

async function handleBarcode(barcode) {
    try {
        const response = await fetch(`/api/products/barcode/${barcode}`);
        const product = await response.json();
        
        if (product) {
            showNotification(`Found product: ${product.name}`, 'success');
            window.location.href = `/products/${product.id}`;
        } else {
            showNotification('Product not found', 'error');
        }
    } catch (error) {
        console.error('Error handling barcode:', error);
        showNotification('Error processing barcode', 'error');
    }
}