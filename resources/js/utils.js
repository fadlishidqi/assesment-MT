window.handleError = function(error) {
    console.error("Detail Error:", error);
    let pesan = "Terjadi kesalahan pada sistem.";

    if (!navigator.onLine) {
        pesan = "Gagal koneksi ke server. Periksa jaringan internet Anda.";
    } else if (error.name === 'AbortError') {
        pesan = "Timeout request. Server terlalu lama merespons.";
    } else if (error.status) {
        switch(error.status) {
            case 400: pesan = "Validasi input gagal. Periksa kembali data Anda."; break;
            case 404: pesan = "Data tidak ditemukan."; break;
            case 500: pesan = "Internal Server Error. Terjadi masalah di server."; break;
        }
    } else if (error.message) {
        pesan = error.message;
    }

    const toast = document.getElementById('errorToast');
    const toastBody = document.getElementById('errorToastBody');
    
    if (toast && toastBody) {
        toastBody.innerText = pesan;
        toast.classList.remove('translate-y-full', 'opacity-0');
        
        setTimeout(() => {
            toast.classList.add('translate-y-full', 'opacity-0');
        }, 5000);
    }
};

window.showConfirm = function(title, message) {
    return new Promise((resolve) => {
        const modalEl = document.getElementById('customConfirmModal');
        
        if (!modalEl) {
            console.error("Modal element tidak ditemukan!");
            resolve(false);
            return;
        }

        document.getElementById('confirmTitle').innerText = title;
        document.getElementById('confirmMessage').innerText = message;
        
        modalEl.classList.remove('hidden');
        modalEl.classList.add('flex');

        document.getElementById('btnYes').onclick = function() {
            modalEl.classList.add('hidden');
            modalEl.classList.remove('flex');
            resolve(true);
        };
        
        document.getElementById('btnCancel').onclick = function() {
            modalEl.classList.add('hidden');
            modalEl.classList.remove('flex');
            resolve(false);
        };
    });
};