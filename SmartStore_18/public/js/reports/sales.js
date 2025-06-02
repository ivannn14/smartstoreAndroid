document.addEventListener('DOMContentLoaded', function() {
    initializeChart();
    initializeFilters();
    initializeExport();
});

function initializeChart() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    window.salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Sales',
                data: chartData.data,
                borderColor: '#5e72e4',
                backgroundColor: 'rgba(94, 114, 228, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointBackgroundColor: '#5e72e4',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
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
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#000',
                    bodyColor: '#000',
                    borderColor: '#e9ecef',
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
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        borderDash: [5, 5],
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}

function initializeFilters() {
    const form = document.getElementById('reportForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            await updateReport(new FormData(form));
        });
    }
}

async function updateReport(formData) {
    try {
        const response = await fetch('/api/reports/sales/filter', {
            method: 'POST',
            body: formData
        });
        
        if (!response.ok) throw new Error('Failed to fetch data');
        
        const data = await response.json();
        updateChartData(data.chart);
        updateStatsCards(data.stats);
        updateTable(data.table);
        
        showNotification('Report updated successfully', 'success');
    } catch (error) {
        console.error('Error updating report:', error);
        showNotification('Failed to update report', 'error');
    }
}

function updateChartData(chartData) {
    if (window.salesChart) {
        window.salesChart.data.labels = chartData.labels;
        window.salesChart.data.datasets[0].data = chartData.data;
        window.salesChart.update();
    }
}

function updateStatsCards(stats) {
    Object.entries(stats).forEach(([key, value]) => {
        const element = document.querySelector(`[data-stat="${key}"]`);
        if (element) {
            element.querySelector('.stat-value').textContent = value.formatted;
            element.querySelector('.stat-change').textContent = value.change;
        }
    });
}

function updateTable(tableData) {
    const tbody = document.querySelector('.table tbody');
    if (tbody && tableData.length) {
        tbody.innerHTML = tableData.map(row => `
            <tr class="table-row">
                <td>${row.period}</td>
                <td>${row.transactions}</td>
                <td>${row.total_sales}</td>
                <td>${row.average_sale}</td>
                <td>
                    <span class="trend-indicator ${row.trend >= 0 ? 'positive' : 'negative'}">
                        <i class="fas fa-arrow-${row.trend >= 0 ? 'up' : 'down'}"></i>
                        ${Math.abs(row.trend)}%
                    </span>
                </td>
            </tr>
        `).join('');
    }
}

async function exportReport() {
    try {
        const response = await fetch('/api/reports/sales/export', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                period: document.querySelector('[name="period"]').value,
                start_date: document.querySelector('[name="start_date"]').value,
                end_date: document.querySelector('[name="end_date"]').value
            })
        });

        if (!response.ok) throw new Error('Export failed');

        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'sales-report.pdf';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        
        showNotification('Report exported successfully', 'success');
    } catch (error) {
        console.error('Export failed:', error);
        showNotification('Failed to export report', 'error');
    }
}

function showNotification(message, type) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed bottom-0 end-0 m-3`;
    toast.style.zIndex = '1050';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}