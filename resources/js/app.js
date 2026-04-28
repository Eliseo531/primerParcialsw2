import './bootstrap';
import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    if (!window.dashboardData) return;

    const bugsEstado = window.dashboardData.bugsEstado;
    const bugsSeveridad = window.dashboardData.bugsSeveridad;
    const pruebas = window.dashboardData.pruebas;

    const bugsEstadoCanvas = document.getElementById('bugsEstadoChart');
    if (bugsEstadoCanvas) {
        new Chart(bugsEstadoCanvas, {
            type: 'bar',
            data: {
                labels: Object.keys(bugsEstado),
                datasets: [{
                    label: 'Cantidad',
                    data: Object.values(bugsEstado),
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    }

    const pruebasCanvas = document.getElementById('pruebasChart');
    if (pruebasCanvas) {
        new Chart(pruebasCanvas, {
            type: 'doughnut',
            data: {
                labels: Object.keys(pruebas),
                datasets: [{
                    data: Object.values(pruebas),
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
            }
        });
    }

    const severidadCanvas = document.getElementById('severidadChart');
    if (severidadCanvas) {
        new Chart(severidadCanvas, {
            type: 'pie',
            data: {
                labels: Object.keys(bugsSeveridad),
                datasets: [{
                    data: Object.values(bugsSeveridad),
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    }
});
