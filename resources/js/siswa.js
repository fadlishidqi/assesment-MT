window.allDataSiswa = [];
window.allDataKelas = []; 
window.modalElSiswa = null; 
window.modalElKelas = null; 

// ================= LOAD DATA =================
window.loadDataSiswa = async function() {
    try {
        const response = await fetch('/api/v1/siswa');
        if (!response.ok) throw response;
        const result = await response.json();
        window.allDataSiswa = result.data;
        renderTableSiswa(window.allDataSiswa);
        populateFilterKelas(window.allDataSiswa);
    } catch (error) {
        document.getElementById('tbody-siswa').innerHTML = '<tr><td colspan="5" class="py-8 text-center text-sm text-red-500">Gagal memuat data</td></tr>';
        console.error(error);
    }
}

window.loadDataKelasSiswa = async function() {
    try {
        const response = await fetch('/api/v1/kelas');
        if (!response.ok) throw response;
        const result = await response.json();
        window.allDataKelas = result.data;
        
        const selectKelas = document.getElementById('Nid_kelas');
        selectKelas.innerHTML = '<option value="">-- Pilih Kelas --</option>';
        
        window.allDataKelas.forEach(kelas => {
            selectKelas.innerHTML += `<option value="${kelas.Nid_kelas}">${kelas.Vnama_kelas}</option>`;
        });
    } catch (error) {
        console.error('Gagal memuat data kelas:', error);
    }
}

// ================= RENDER & FILTER TABEL =================
function renderTableSiswa(data) {
    const tbody = document.getElementById('tbody-siswa');
    tbody.innerHTML = '';
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="py-8 text-center text-sm text-gray-500">Tidak ada data</td></tr>';
        return;
    }

    data.forEach((siswa, index) => {
        const namaKelas = siswa.kelas ? siswa.kelas.Vnama_kelas : '-';
        const tr = document.createElement('tr');
        tr.className = "hover:bg-gray-50 transition";
        tr.innerHTML = `
            <td class="py-4 px-6 text-sm text-gray-900 text-center">${index + 1}</td>
            <td class="py-4 px-6 text-sm font-medium text-gray-900">${siswa.Nnis}</td>
            <td class="py-4 px-6 text-sm text-gray-700">${siswa.Vnama}</td>
            <td class="py-4 px-6 text-sm text-gray-700">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${namaKelas}</span>
            </td>
            <td class="py-4 px-6 text-sm text-center flex justify-center space-x-2">
                <button onclick='window.showModalSiswa("edit", ${JSON.stringify(siswa)})' class="text-amber-500 hover:text-amber-700 transition bg-amber-50 p-1.5 rounded-md" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                </button>
                <button onclick="window.hapusSiswa(${siswa.Nid_siswa})" class="text-red-500 hover:text-red-700 transition bg-red-50 p-1.5 rounded-md" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function populateFilterKelas(data) {
    const select = document.getElementById('filterKelas');
    const uniqueKelas = [...new Set(data.map(item => item.kelas ? item.kelas.Vnama_kelas : null))].filter(Boolean);
    select.innerHTML = '<option value="">Semua Kelas</option>';
    uniqueKelas.forEach(kelas => select.innerHTML += `<option value="${kelas}">${kelas}</option>`);
}

window.filterTable = function() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const filterKelas = document.getElementById('filterKelas').value;
    const filteredData = window.allDataSiswa.filter(siswa => {
        const matchSearch = siswa.Vnama.toLowerCase().includes(search) || siswa.Nnis.toString().includes(search);
        const namaKelas = siswa.kelas ? siswa.kelas.Vnama_kelas : '';
        return matchSearch && (filterKelas === '' || namaKelas === filterKelas);
    });
    renderTableSiswa(filteredData);
}

// ================= CRUD SISWA =================
window.showModalSiswa = function(mode, data = null) {
    document.getElementById('formSiswa').reset();
    if (mode === 'tambah') {
        document.getElementById('modalTitle').innerText = 'Tambah Siswa Baru';
        document.getElementById('Nid_siswa').value = '';
    } else {
        document.getElementById('modalTitle').innerText = 'Edit Siswa';
        document.getElementById('Nid_siswa').value = data.Nid_siswa;
        document.getElementById('Nnis').value = data.Nnis;
        document.getElementById('Vnama').value = data.Vnama;
        document.getElementById('Nid_kelas').value = data.Nid_kelas;
    }
    window.modalElSiswa.classList.remove('hidden');
    window.modalElSiswa.classList.add('flex');
}

window.hideModalSiswa = function() {
    window.modalElSiswa.classList.add('hidden');
    window.modalElSiswa.classList.remove('flex');
}

window.simpanSiswa = function() {
    const id = document.getElementById('Nid_siswa').value;
    const url = id ? `/api/v1/siswa/${id}` : '/api/v1/siswa';
    const method = id ? 'PUT' : 'POST';
    const payload = {
        Nnis: document.getElementById('Nnis').value,
        Vnama: document.getElementById('Vnama').value,
        Nid_kelas: document.getElementById('Nid_kelas').value
    };

    if (!payload.Nnis || !payload.Vnama || !payload.Nid_kelas) {
        Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Pastikan semua kolom telah diisi!' });
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Penyimpanan',
        text: "Apakah data siswa yang Anda masukkan sudah benar?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                
                if (!response.ok) {
                    const errData = await response.json();
                    throw new Error(errData.message || 'Terjadi kesalahan saat menyimpan data');
                }
                
                Swal.fire('Berhasil!', 'Data siswa berhasil disimpan.', 'success');
                window.hideModalSiswa();
                window.loadDataSiswa(); 
            } catch (error) { 
                Swal.fire('Gagal!', error.message, 'error');
            }
        }
    });
}

window.hapusSiswa = function(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data siswa ini beserta semua nilainya akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await fetch(`/api/v1/siswa/${id}`, { method: 'DELETE' });
                if (!response.ok) throw response;
                
                Swal.fire('Terhapus!', 'Data siswa berhasil dihapus.', 'success');
                window.loadDataSiswa(); 
            } catch (error) { 
                Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data siswa.', 'error');
            }
        }
    });
}

// ================= TAMBAH KELAS =================
window.showModalKelas = function() {
    document.getElementById('formKelas').reset();
    window.modalElKelas.classList.remove('hidden');
    window.modalElKelas.classList.add('flex');
}

window.hideModalKelas = function() {
    window.modalElKelas.classList.add('hidden');
    window.modalElKelas.classList.remove('flex');
}

window.simpanKelas = function() {
    const namaKelas = document.getElementById('Vnama_kelas').value;

    if (!namaKelas) {
        Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Nama kelas tidak boleh kosong!' });
        return;
    }

    Swal.fire({
        title: 'Simpan Kelas?',
        text: `Tambahkan kelas "${namaKelas}" ke dalam sistem?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981', 
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await fetch('/api/v1/kelas', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ 
                        nama_kelas: namaKelas, 
                        Vnama_kelas: namaKelas 
                    })
                });
                
                if (!response.ok) {
                    const errData = await response.json();
                    let errorMsg = errData.message || 'Gagal menyimpan data kelas';
                    if (errData.errors) {
                        errorMsg = Object.values(errData.errors)[0][0]; 
                    }
                    throw new Error(errorMsg);
                }
                
                Swal.fire('Berhasil!', 'Kelas baru berhasil ditambahkan.', 'success');
                window.hideModalKelas();
                window.loadDataKelasSiswa(); 
            } catch (error) { 
                Swal.fire('Gagal!', error.message, 'error');
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    window.modalElSiswa = document.getElementById('modalSiswa');
    window.modalElKelas = document.getElementById('modalKelas'); 
    window.loadDataSiswa();
    window.loadDataKelasSiswa();
});