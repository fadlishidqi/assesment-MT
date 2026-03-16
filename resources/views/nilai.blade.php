@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Manajemen Nilai</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola data nilai UH, UTS, dan UAS siswa</p>
    </div>
    <button onclick="showModalNilai('tambah')" class="bg-primary hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium text-sm shadow-sm transition flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Nilai
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">No</th>
                    <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                    <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                    <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Periode</th>
                    <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">UH</th>
                    <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">UTS</th>
                    <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">UAS</th>
                    <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="tbody-nilai" class="divide-y divide-gray-100">
                <tr><td colspan="8" class="py-8 text-center text-sm text-gray-500">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div id="modalNilai" class="hidden fixed inset-0 z-50 items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="hideModalNilai()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900" id="modalTitle">Input Nilai Siswa</h3>
            <button onclick="hideModalNilai()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form id="formNilai" class="space-y-5">
                <input type="hidden" id="Nid_nilai">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Siswa</label>
                        <select id="Nid_siswa" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                            <option value="">-- Memuat Data Siswa... --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                        <select id="Nid_mapel" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                            <option value="">-- Memuat Data Mapel... --</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
                        <select id="Vtahun_ajaran" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                            <option value="2025/2026">2025/2026</option>
                            <option value="2026/2027">2026/2027</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                        <select id="Vsemester" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 border-t border-gray-100 pt-4 mt-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nilai UH</label>
                        <input type="number" id="Nuh" min="0" max="100" required placeholder="0-100" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition text-center font-bold text-gray-800">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nilai UTS</label>
                        <input type="number" id="Nuts" min="0" max="100" required placeholder="0-100" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition text-center font-bold text-gray-800">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nilai UAS</label>
                        <input type="number" id="Nuas" min="0" max="100" required placeholder="0-100" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition text-center font-bold text-gray-800">
                    </div>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end p-6 border-t border-gray-100 space-x-3">
            <button type="button" onclick="hideModalNilai()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-sm">Batal</button>
            <button type="button" onclick="simpanNilai()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm shadow-sm">Simpan Nilai</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modalEl = document.getElementById('modalNilai');

    // Fetch dan render daftar Nilai di Tabel
    async function loadDataNilai() {
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
                        <button onclick='showModalNilai("edit", ${JSON.stringify(item)})' class="text-amber-500 hover:text-amber-700 bg-amber-50 p-1.5 rounded-md" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <button onclick="hapusNilai(${item.Nid_nilai})" class="text-red-500 hover:text-red-700 bg-red-50 p-1.5 rounded-md" title="Hapus">
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

    // Fetch daftar siswa untuk dropdown Modal
    async function loadDropdownSiswa() {
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

    // Fetch daftar mapel untuk dropdown Modal
    async function loadDropdownMapel() {
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

    // Manajemen Buka/Tutup Modal
    function showModalNilai(mode, data = null) {
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
        modalEl.classList.remove('hidden');
        modalEl.classList.add('flex');
    }

    function hideModalNilai() {
        modalEl.classList.add('hidden');
        modalEl.classList.remove('flex');
    }

    // Simpan data (Create / Update)
    async function simpanNilai() {
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

        try {
            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });
            
            if (!response.ok) {
                const errData = await response.json();
                alert(errData.message || 'Gagal menyimpan nilai');
                throw response;
            }
            
            hideModalNilai();
            loadDataNilai();
        } catch (error) { 
            console.error(error); 
        }
    }

    // Hapus data
    async function hapusNilai(id) {
        if (!confirm("Apakah Anda yakin ingin menghapus data nilai ini?")) return;
        try {
            const response = await fetch(`/api/v1/nilai/${id}`, { method: 'DELETE' });
            if (!response.ok) throw response;
            loadDataNilai();
        } catch (error) { 
            console.error(error);
            alert('Gagal menghapus nilai');
        }
    }

    // Init script
    document.addEventListener('DOMContentLoaded', () => {
        loadDataNilai();
        loadDropdownSiswa();
        loadDropdownMapel();
    });
</script>
@endpush