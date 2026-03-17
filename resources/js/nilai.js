window.modalElNilai = null;

window.loadDataNilai = async function() {
    try {
        const response = await fetch('/api/v1/nilai');
        if (!response.ok) throw response;
        const result = await response.json();
        
        const tbody = document.getElementById('tbody-nilai');
        tbody.innerHTML = '';
        
        if (result.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="py-8 text-center text-sm text-gray-500">Tidak ada data nilai</td></tr>';
            return;
        }

        result.data.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.className = "hover:bg-gray-50 transition";
            tr.innerHTML = `
                <td class="py-3 px-4 text-sm text-gray-900 text-center">${index + 1}</td>
                <td class="py-3 px-4 text-sm font-bold text-gray-800">${item.siswa ? item.siswa.Vnama : '-'}</td>
                <td class="py-3 px-4 text-sm text-gray-700">${item.mapel ? item.mapel.Vnama_mapel : '-'}</td>
                <td class="py-3 px-4 text-sm text-center">
                    <span class="text-xs text-gray-500 block">${item.Vtahun_ajaran}</span>
                    <span class="text-xs font-semibold text-primary block">${item.Vsemester}</span>
                </td>
                <td class="py-3 px-4 text-sm font-medium text-gray-900 text-center">${item.Nuh}</td>
                <td class="py-3 px-4 text-sm font-medium text-gray-900 text-center">${item.Nuts}</td>
                <td class="py-3 px-4 text-sm font-medium text-gray-900 text-center">${item.Nuas}</td>
                <td class="py-3 px-4 text-sm text-center flex justify-center space-x-2">
                    <button onclick='window.showModalNilai("edit", ${JSON.stringify(item)})' class="text-amber-500 hover:text-amber-700 bg-amber-50 p-1.5 rounded-md" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                    <button onclick="window.hapusNilai(${item.Nid_nilai})" class="text-red-500 hover:text-red-700 bg-red-50 p-1.5 rounded-md" title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        document.getElementById('tbody-nilai').innerHTML = '<tr><td colspan="8" class="py-8 text-center text-sm text-red-500">Gagal memuat data</td></tr>';
        console.error(error);
    }
}

window.loadDropdownSiswa = async function() {
    try {
        const res = await fetch('/api/v1/siswa');
        const result = await res.json();
        const select = document.getElementById('Nid_siswa');
        select.innerHTML = '<option value="">-- Pilih Siswa --</option>';
        result.data.forEach(siswa => {
            select.innerHTML += `<option value="${siswa.Nid_siswa}">${siswa.Nnis} - ${siswa.Vnama}</option>`;
        });
    } catch (e) { console.error('Gagal load dropdown siswa', e); }
}

window.loadDropdownMapel = async function() {
    try {
        const res = await fetch('/api/v1/mapel');
        const result = await res.json();
        const select = document.getElementById('Nid_mapel');
        select.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
        result.data.forEach(mapel => {
            select.innerHTML += `<option value="${mapel.Nid_mapel}">${mapel.Vnama_mapel}</option>`;
        });
    } catch (e) { console.error('Gagal load dropdown mapel', e); }
}

window.showModalNilai = function(mode, data = null) {
    document.getElementById('formNilai').reset();
    if (mode === 'tambah') {
        document.getElementById('modalTitle').innerText = 'Input Nilai Baru';
        document.getElementById('Nid_nilai').value = '';
    } else {
        document.getElementById('modalTitle').innerText = 'Edit Nilai Siswa';
        document.getElementById('Nid_nilai').value = data.Nid_nilai;
        document.getElementById('Nid_siswa').value = data.Nid_siswa;
        document.getElementById('Nid_mapel').value = data.Nid_mapel;
        document.getElementById('Vtahun_ajaran').value = data.Vtahun_ajaran;
        document.getElementById('Vsemester').value = data.Vsemester;
        document.getElementById('Nuh').value = data.Nuh;
        document.getElementById('Nuts').value = data.Nuts;
        document.getElementById('Nuas').value = data.Nuas;
    }
    window.modalElNilai.classList.remove('hidden');
    window.modalElNilai.classList.add('flex');
}

window.hideModalNilai = function() {
    window.modalElNilai.classList.add('hidden');
    window.modalElNilai.classList.remove('flex');
}

window.simpanNilai = function() {
    const id = document.getElementById('Nid_nilai').value;
    const url = id ? `/api/v1/nilai/${id}` : '/api/v1/nilai';
    const method = id ? 'PUT' : 'POST';
    
    const payload = {
        Nid_siswa: document.getElementById('Nid_siswa').value,
        Nid_mapel: document.getElementById('Nid_mapel').value,
        Vtahun_ajaran: document.getElementById('Vtahun_ajaran').value,
        Vsemester: document.getElementById('Vsemester').value,
        Nuh: document.getElementById('Nuh').value,
        Nuts: document.getElementById('Nuts').value,
        Nuas: document.getElementById('Nuas').value
    };

    // Validasi kosong
    if (!payload.Nid_siswa || !payload.Nid_mapel || !payload.Vtahun_ajaran || !payload.Vsemester || payload.Nuh === '' || payload.Nuts === '' || payload.Nuas === '') {
        Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Pastikan semua kolom telah diisi!' });
        return;
    }

    // ================= VALIDASI NILAI 0 - 100 =================
    const uh = parseFloat(payload.Nuh);
    const uts = parseFloat(payload.Nuts);
    const uas = parseFloat(payload.Nuas);

    if (uh < 0 || uh > 100 || uts < 0 || uts > 100 || uas < 0 || uas > 100) {
        Swal.fire({
            icon: 'error',
            title: 'Nilai Tidak Valid!',
            text: 'Nilai UH, UTS, dan UAS wajib diisi dengan rentang angka 0 hingga 100.'
        });
        return;
    }
    // =========================================================

    Swal.fire({
        title: 'Konfirmasi Penyimpanan',
        text: "Pastikan data nilai sudah benar sebelum disimpan.",
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
                    throw new Error(errData.message || 'Gagal menyimpan nilai');
                }
                
                Swal.fire('Berhasil!', 'Data nilai berhasil disimpan.', 'success');
                window.hideModalNilai();
                window.loadDataNilai();
            } catch (error) { 
                Swal.fire('Gagal!', error.message, 'error');
            }
        }
    });
}

window.hapusNilai = function(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data nilai ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await fetch(`/api/v1/nilai/${id}`, { method: 'DELETE' });
                if (!response.ok) throw response;
                
                Swal.fire('Terhapus!', 'Data nilai berhasil dihapus.', 'success');
                window.loadDataNilai();
            } catch (error) { 
                console.error(error);
                Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus nilai.', 'error');
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    window.modalElNilai = document.getElementById('modalNilai');
    window.loadDataNilai();
    window.loadDropdownSiswa();
    window.loadDropdownMapel();
});