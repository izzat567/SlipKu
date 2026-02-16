document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('studentResultForm');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const successMessage = document.getElementById('successMessage');
    const tahunSelect = document.getElementById('id_kelas');
    const kelasSelect = document.getElementById('kelas');

    // Sembunyikan loading overlay dan success message pada awalnya
    loadingOverlay.style.display = 'none';
    successMessage.style.display = 'none';

    // Sinkronisasi Tahun dan Kelas
    tahunSelect.addEventListener('change', function() {
        const selectedYear = this.value;
        // Reset pilihan kelas
        kelasSelect.value = '';
        
        // Sembunyikan semua option
        Array.from(kelasSelect.options).forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }
            // Kelas option format: "1 ALPHA", "2 BETA", dsb.
            const kelasYear = option.value.split(' ')[0];
            if (kelasYear === selectedYear) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    });

    // Format No. KP: Hilangkan dash dan hadkan kepada 12 digit
    const noKpInput = document.getElementById('no_kp');
    noKpInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 12) {
            value = value.substring(0, 12);
        }
        this.value = value;
    });

    // Handle form submission
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        // Validasi tambahan jika diperlukan
        if (!validateForm()) {
            return;
        }

        // Tampilkan loading overlay
        loadingOverlay.style.display = 'flex';

        // Simulasikan proses pengiriman data (dalam aplikasi sebenar, ini adalah AJAX atau langsung submit)
        setTimeout(() => {
            loadingOverlay.style.display = 'none';
            successMessage.style.display = 'block';
            
            // Setelah 2 detik, submit form secara manual
            setTimeout(() => {
                form.submit();
            }, 2000);
        }, 1500);
    });

    function validateForm() {
        // Validasi No. KP harus 12 digit
        const noKp = noKpInput.value;
        if (noKp.length !== 12) {
            alert('No. Kad Pengenalan mesti 12 digit.');
            return false;
        }

        // Pastikan tahun dan kelas dipilih
        if (!tahunSelect.value) {
            alert('Sila pilih tahun.');
            return false;
        }

        if (!kelasSelect.value) {
            alert('Sila pilih kelas.');
            return false;
        }

        // Validasi lain jika diperlukan
        return true;
    }

    // Reset form akan menyembunyikan pesan kejayaan
    form.addEventListener('reset', function() {
        successMessage.style.display = 'none';
    });
});