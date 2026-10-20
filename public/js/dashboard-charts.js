// Dashboard Charts Initializer for SPWM-Collab
function initDashboardCharts(data) {
    if (!data) return;

    // Chart 1: Document Type Doughnut Chart
    const typeElem = document.getElementById('typeChart');
    if (typeElem && typeof Chart !== 'undefined') {
        const ctxType = typeElem.getContext('2d');
        new Chart(ctxType, {
            type: 'doughnut',
            data: {
                labels: ['MoU (Nota Kesepahaman)', 'MoA (Perjanjian Kerjasama)', 'IA (Implementation Arrangement)'],
                datasets: [{
                    data: [data.mouCount || 0, data.moaCount || 0, data.iaCount || 0],
                    backgroundColor: ['#059669', '#2563eb', '#9333ea'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { family: 'Plus Jakarta Sans', size: 11, weight: 'bold' } }
                    }
                }
            }
        });
    }

    // Chart 2: Status Bar Chart
    const statusElem = document.getElementById('statusChart');
    if (statusElem && typeof Chart !== 'undefined') {
        const ctxStatus = statusElem.getContext('2d');
        new Chart(ctxStatus, {
            type: 'bar',
            data: {
                labels: ['Aktif', 'Akan Berakhir', 'Kedaluwarsa', 'Dalam Perpanjangan'],
                datasets: [{
                    label: 'Jumlah Dokumen',
                    data: [
                        data.aktifCount || 0,
                        data.akanBerakhirCount || 0,
                        data.kedaluwarsaCount || 0,
                        data.perpanjanganCount || 0
                    ],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#0284c7'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }
}

window.initDashboardCharts = initDashboardCharts;
