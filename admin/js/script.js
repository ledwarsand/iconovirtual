document.addEventListener('DOMContentLoaded', function () {
    // 1. Chart.js Initialization
    const ctx = document.getElementById('leadsChart');
    let leadsChart;
    if (ctx) {
        leadsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Leads por Servicio',
                    data: chartData.data,
                    backgroundColor: '#1f6feb',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 }
                    }
                }
            }
        });
    }

    // 2. Search and Filter Logic
    const searchInput = document.getElementById('leadSearch');
    const serviceFilter = document.getElementById('serviceFilter');
    const dateFilter = document.getElementById('dateFilter');
    const applyBtn = document.getElementById('applyFilters');
    const clearBtn = document.getElementById('clearFilters');
    const tableRows = document.querySelectorAll('.lead-row');

    function updateChart() {
        if (!leadsChart) return;

        const counts = {};
        chartData.labels.forEach(label => counts[label] = 0);

        tableRows.forEach(row => {
            if (row.style.display !== 'none') {
                const svc = row.getAttribute('data-service');
                if (counts.hasOwnProperty(svc)) {
                    counts[svc]++;
                }
            }
        });

        leadsChart.data.datasets[0].data = chartData.labels.map(label => counts[label]);
        leadsChart.update();
    }

    function filterLeads() {
        const searchTerm = searchInput.value.toLowerCase();
        const filterValue = serviceFilter.value;
        const dateValue = dateFilter.value;

        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            const rowService = row.getAttribute('data-service');
            const rowDate = row.getAttribute('data-date');

            const matchesSearch = rowText.includes(searchTerm);
            const matchesFilter = !filterValue || rowService === filterValue;
            const matchesDate = !dateValue || rowDate === dateValue;

            row.style.display = (matchesSearch && matchesFilter && matchesDate) ? '' : 'none';
        });

        updateChart();
    }

    if (applyBtn) {
        applyBtn.addEventListener('click', filterLeads);
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            if (serviceFilter) serviceFilter.value = '';
            if (dateFilter) dateFilter.value = '';
            tableRows.forEach(row => row.style.display = '');
            updateChart();
        });
    }

    // 3. Tracking Toggles
    const toggles = document.querySelectorAll('.tracking-toggle');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function () {
            const key = this.getAttribute('data-key');
            const enabled = this.checked;
            const label = this.nextElementSibling;

            const formData = new FormData();
            formData.append('action', 'toggle_tracking');
            formData.append('key', key);
            formData.append('enabled', enabled);

            fetch('api.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        label.textContent = enabled ? 'Habilitado' : 'Deshabilitado';
                    } else {
                        alert('Error: ' + data.error);
                        this.checked = !enabled; // Revert
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error de conexión');
                    this.checked = !enabled;
                });
        });
    });

    // 4. Content Form Submission
    const contentForm = document.getElementById('contentForm');
    if (contentForm) {
        contentForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Guardando...';

            const formData = new FormData(this);
            formData.append('action', 'update_content');

            fetch('api.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('¡Contenido actualizado correctamente!');
                    } else {
                        alert('Error: ' + data.error);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error al guardar');
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                });
        });
    }
});
