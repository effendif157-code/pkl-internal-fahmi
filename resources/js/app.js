import './bootstrap';

// Import Bootstrap JS
import * as bootstrap from "bootstrap";
window.bootstrap = bootstrap;

document.addEventListener("DOMContentLoaded", function () {
    // 1. Flash Message Auto-dismiss
    const alerts = document.querySelectorAll(".alert-dismissible");
    alerts.forEach(function (alert) {
        setTimeout(function () {
            try {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            } catch (e) {
                // Menghindari error jika alert sudah ditutup manual
                alert.remove();
            }
        }, 5000);
    });

    // 2. Perbaikan Tombol Status/Switch (Opsional: AJAX)
    // Jika Anda ingin tombol "Aktif" langsung tersimpan tanpa reload halaman
    const statusSwitches = document.querySelectorAll('.status-switch');
    statusSwitches.forEach(sw => {
        sw.addEventListener('change', function () {
            const id = this.dataset.id;
            const url = this.dataset.url;

            // Contoh sederhana pengiriman status via fetch
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: this.checked })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Status updated:', data.message);
                })
                .catch(error => console.error('Error:', error));
        });
    });
});