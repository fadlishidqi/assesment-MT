@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Manajemen Siswa</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola data induk siswa dan kelas di sini</p>
    </div>
    
    <div class="flex space-x-3 w-full md:w-auto">
        <button onclick="window.showModalKelas()" class="w-full md:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg font-medium text-sm shadow-sm transition flex items-center justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Kelas
        </button>
        <button onclick="window.showModalSiswa('tambah')" class="w-full md:w-auto bg-primary hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium text-sm shadow-sm transition flex items-center justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Siswa
        </button>
    </div>
</div>

<div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pencarian</label>
            <input type="text" id="searchInput" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" placeholder="Cari nama atau NIS siswa..." onkeyup="window.filterTable()">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Filter Kelas</label>
            <select id="filterKelas" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" onchange="window.filterTable()">
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
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="window.hideModalSiswa()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900" id="modalTitle">Tambah Siswa Baru</h3>
            <button onclick="window.hideModalSiswa()" class="text-gray-400 hover:text-gray-600 transition">
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <select id="Nid_kelas" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                        <option value="">-- Memuat Kelas... --</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end p-6 border-t border-gray-100 space-x-3">
            <button type="button" onclick="window.hideModalSiswa()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-sm">Batal</button>
            <button type="button" onclick="window.simpanSiswa()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm shadow-sm">Simpan Data</button>
        </div>
    </div>
</div>

<div id="modalKelas" class="hidden fixed inset-0 z-50 items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="window.hideModalKelas()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Tambah Kelas Baru</h3>
            <button onclick="window.hideModalKelas()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto">
            <form id="formKelas" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas</label>
                    <input type="text" id="Vnama_kelas" required placeholder="Contoh: 12 IPA 1" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end p-6 border-t border-gray-100 space-x-3">
            <button type="button" onclick="window.hideModalKelas()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-sm">Batal</button>
            <button type="button" onclick="window.simpanKelas()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium text-sm shadow-sm">Simpan Kelas</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/siswa.js')
@endpush