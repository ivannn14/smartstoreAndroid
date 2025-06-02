document.addEventListener('DOMContentLoaded', function() {
    initializeChart();
    initializeButtons();
});

function initializeChart() {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Performance',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: '#5e72e4',
                backgroundColor: 'rgba(94, 114, 228, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#1a1f37',
                    bodyColor: '#1a1f37',
                    borderColor: '#e9ecef',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        borderDash: [2, 2]
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Store chart instance for later use
    window.performanceChart = chart;
}

function initializeButtons() {
    const exportBtn = document.querySelector('.export-btn');
    const refreshBtn = document.querySelector('.refresh-btn');

    if (exportBtn) {
        exportBtn.addEventListener('click', exportReport);
    }

    if (refreshBtn) {
        refreshBtn.addEventListener('click', refreshData);
    }
}

async function exportReport() {
    try {
        const response = await fetch('/api/reports/performance/export');
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'performance-report.pdf';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        showNotification('Report exported successfully!', 'success');
    } catch (error) {
        console.error('Export failed:', error);
        showNotification('Failed to export report', 'error');
    }
}

async function refreshData() {
    try {
        const refreshBtn = document.querySelector('.refresh-btn');
        refreshBtn.disabled = true;
        refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Refreshing...';

        const response = await fetch('/api/reports/performance/data');
        const data = await response.json();

        updateChartData(data);
        updateStatsCards(data);
        updateTable(data);

        showNotification('Data refreshed successfully!', 'success');
    } catch (error) {
        console.error('Refresh failed:', error);
        showNotification('Failed to refresh data', 'error');
    } finally {
        const refreshBtn = document.querySelector('.refresh-btn');
        refreshBtn.disabled = false;
        refreshBtn.innerHTML = '<i class="fas fa-sync-alt me-2"></i>Refresh Data';
    }
}

function updateChartData(data) {
    if (window.performanceChart) {
        window.performanceChart.data.labels = data.labels;
        window.performanceChart.data.datasets[0].data = data.values;
        window.performanceChart.update();
    }
}

function updateStatsCards(data) {
    // Update stats cards with new data
    Object.entries(data.stats).forEach(([key, value]) => {
        const card = document.querySelector(`[data-stat="${key}"]`);
        if (card) {
            card.querySelector('h3').textContent = value.current;
            card.querySelector('.trend').textContent = value.trend;
        }
    });
}

function updateTable(data) {
    const tbody = document.querySelector('table tbody');
    if (tbody && data.tableData) {
        tbody.innerHTML = data.tableData.map(row => `
            <tr>
                <td>${row.metric}</td>
                <td>${row.current}</td>
                <td>${row.previous}</td>
                <td>${row.change}</td>
                <td><span class="badge bg-${row.status.color}">${row.status.label}</span></td>
            </tr>
        `).join('');
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