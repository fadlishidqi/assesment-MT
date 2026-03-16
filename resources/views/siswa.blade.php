@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Manajemen Siswa</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola data induk siswa di sini</p>
    </div>
    <button onclick="showModalSiswa('tambah')" class="bg-primary hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium text-sm shadow-sm transition flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Siswa
    </button>
</div>

<div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pencarian</label>
            <input type="text" id="searchInput" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" placeholder="Cari nama atau NIS siswa..." onkeyup="filterTable()">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Filter Kelas</label>
            <select id="filterKelas" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" onchange="filterTable()">
                <option value="">Semua Kelas</option>
            </select>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16 text-center">No</th>
                    <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">NIS</th>
                    <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                    <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas</th>
                    <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody id="tbody-siswa" class="divide-y divide-gray-100">
                <tr><td colspan="5" class="py-8 text-center text-sm text-gray-500">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div id="modalSiswa" class="hidden fixed inset-0 z-50 items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="hideModalSiswa()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900" id="modalTitle">Tambah Siswa Baru</h3>
            <button onclick="hideModalSiswa()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form id="formSiswa" class="space-y-4">
                <input type="hidden" id="Nid_siswa">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIS</label>
                    <input type="number" id="Nnis" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="Vnama" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Kelas</label>
                    <input type="number" id="Nid_kelas" required placeholder="Contoh: 1" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end p-6 border-t border-gray-100 space-x-3">
            <button type="button" onclick="hideModalSiswa()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-sm">Batal</button>
            <button type="button" onclick="simpanSiswa()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm shadow-sm">Simpan Data</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let allDataSiswa = [];
    const modalEl = document.getElementById('modalSiswa');

    async function loadDataSiswa() {
        try {
            const response = await fetch('/api/v1/siswa');
            if (!response.ok) throw response;
            const result = await response.json();
            allDataSiswa = result.data;
            renderTable(allDataSiswa);
            populateFilterKelas(allDataSiswa);
        } catch (error) {
            document.getElementById('tbody-siswa').innerHTML = '<tr><td colspan="5" class="py-8 text-center text-sm text-red-500">Gagal memuat data</td></tr>';
            handleError(error);
        }
    }

    function renderTable(data) {
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
                    <button onclick='showModalSiswa("edit", ${JSON.stringify(siswa)})' class="text-amber-500 hover:text-amber-700 transition bg-amber-50 p-1.5 rounded-md" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                    <button onclick="hapusSiswa(${siswa.Nid_siswa})" class="text-red-500 hover:text-red-700 transition bg-red-50 p-1.5 rounded-md" title="Hapus">
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

    function filterTable() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const filterKelas = document.getElementById('filterKelas').value;
        const filteredData = allDataSiswa.filter(siswa => {
            const matchSearch = siswa.Vnama.toLowerCase().includes(search) || siswa.Nnis.toString().includes(search);
            const namaKelas = siswa.kelas ? siswa.kelas.Vnama_kelas : '';
            return matchSearch && (filterKelas === '' || namaKelas === filterKelas);
        });
        renderTable(filteredData);
    }

    // Fungsi Modal Tailwind
    function showModalSiswa(mode, data = null) {
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
        modalEl.classList.remove('hidden');
        modalEl.classList.add('flex');
    }

    function hideModalSiswa() {
        modalEl.classList.add('hidden');
        modalEl.classList.remove('flex');
    }

    async function simpanSiswa() {
        const id = document.getElementById('Nid_siswa').value;
        const url = id ? `/api/v1/siswa/${id}` : '/api/v1/siswa';
        const method = id ? 'PUT' : 'POST';
        const payload = {
            Nnis: document.getElementById('Nnis').value,
            Vnama: document.getElementById('Vnama').value,
            Nid_kelas: document.getElementById('Nid_kelas').value
        };

        try {
            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });
            if (!response.ok) throw response;
            
            hideModalSiswa();
            loadDataSiswa();
        } catch (error) { handleError(error); }
    }

    async function hapusSiswa(id) {
        const isConfirmed = await showConfirm("Hapus Siswa", "Apakah Anda yakin ingin menghapus data siswa ini beserta semua nilainya?");
        if (!isConfirmed) return;
        try {
            const response = await fetch(`/api/v1/siswa/${id}`, { method: 'DELETE' });
            if (!response.ok) throw response;
            loadDataSiswa();
        } catch (error) { handleError(error); }
    }

    document.addEventListener('DOMContentLoaded', loadDataSiswa);
</script>
@endpush