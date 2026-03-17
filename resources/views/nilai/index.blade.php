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
    @vite('resources/js/nilai.js')
@endpush