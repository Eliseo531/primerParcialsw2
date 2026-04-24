import './bootstrap';
import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const bugsChartEl = document.getElementById('bugsChart');
    if (bugsChartEl) {
        new Chart(bugsChartEl, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Bugs',
                        data: [12, 19, 10, 15, 22, 18, 11, 9, 14, 21, 17, 20],
                        borderRadius: 8,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });
    }

    const testsChartEl = document.getElementById('testsChart');
    if (testsChartEl) {
        new Chart(testsChartEl, {
            type: 'doughnut',
            data: {
                labels: ['OK', 'FAIL'],
                datasets: [
                    {
                        data: [83, 17],
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
