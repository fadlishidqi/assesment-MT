@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Laporan Nilai Siswa</h2>
        <p class="text-gray-500 text-sm mt-1">Rekapitulasi dan grafik distribusi predikat</p>
    </div>
    <button onclick="window.exportToCSV()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg font-medium text-sm shadow-sm transition flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
        Export CSV
    </button>
</div>

<div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tahun Ajaran</label>
            <select id="filterTahun" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                <option value="2025/2026">2025/2026</option>
                <option value="2026/2027">2026/2027</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Semester</label>
            <select id="filterSemester" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                <option value="Ganjil">Ganjil</option>
                <option value="Genap">Genap</option>
            </select>
        </div>
        <div class="flex items-end">
            <button onclick="window.loadDataLaporan()" class="w-full bg-secondary hover:bg-gray-800 text-white px-4 py-2.5 rounded-lg font-medium text-sm shadow-sm transition">
                Terapkan Filter
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-sm">Data Nilai Siswa</h3>
            <span class="text-[10px] text-gray-400 font-medium">*Klik nama kelas untuk detail per kelas</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="tabelLaporan">
                <thead class="bg-white border-b border-gray-100">
                    <tr>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">No</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">NIS</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Rata-rata</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Predikat</th>
                    </tr>
                </thead>
                <tbody id="tbody-laporan" class="divide-y divide-gray-100">
                    <tr><td colspan="6" class="py-8 text-center text-sm text-gray-500">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-800 text-sm">Distribusi Predikat (Global)</h3>
        </div>
        <div class="p-6">
            <canvas id="predikatChart"></canvas>
        </div>
    </div>
</div>

<div id="modalDetailKelas" class="hidden fixed inset-0 z-50 items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="window.closeModalKelas()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 flex flex-col max-h-[85vh]">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <div>
                <h3 class="text-lg font-bold text-gray-900" id="titleKelas">Detail Kelas</h3>
                <p class="text-xs text-gray-500 mt-1" id="subtitleKelas">Data statistik nilai per kelas</p>
            </div>
            <button onclick="window.closeModalKelas()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto bg-gray-50">
            <div id="contentKelas" class="space-y-4"></div>
        </div>
        <div class="flex items-center justify-end p-4 border-t border-gray-100">
            <button onclick="window.closeModalKelas()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium text-sm">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite('resources/js/laporan.js')
@endpush