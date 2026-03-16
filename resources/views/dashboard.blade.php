@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Dashboard Statistik</h2>
    <p class="text-gray-500 text-sm mt-1">Ringkasan Sistem Manajemen Data Sekolah</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition duration-200 relative overflow-hidden">
        <div class="absolute right-0 top-0 h-full w-2 bg-blue-500"></div>
        <h6 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Siswa</h6>
        <h2 class="text-4xl font-extrabold text-gray-800" id="stat-siswa">...</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition duration-200 relative overflow-hidden">
        <div class="absolute right-0 top-0 h-full w-2 bg-emerald-500"></div>
        <h6 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Kelas</h6>
        <h2 class="text-4xl font-extrabold text-gray-800" id="stat-kelas">...</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition duration-200 relative overflow-hidden">
        <div class="absolute right-0 top-0 h-full w-2 bg-amber-500"></div>
        <h6 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Mapel</h6>
        <h2 class="text-4xl font-extrabold text-gray-800" id="stat-mapel">...</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition duration-200 relative overflow-hidden">
        <div class="absolute right-0 top-0 h-full w-2 bg-rose-500"></div>
        <h6 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Rata-rata Nilai</h6>
        <h2 class="text-4xl font-extrabold text-gray-800" id="stat-rata">...</h2>
    </div>
</div>
@endsection

@push('scripts')
<script>
    async function loadDashboardData() {
        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 8000); 

            const response = await fetch('/api/v1/dashboard-stats', {
                method: 'GET',
                signal: controller.signal,
                headers: { 'Accept': 'application/json' }
            });
            clearTimeout(timeoutId);

            if (!response.ok) throw response; 

            const result = await response.json();
            const stats = result.data;

            document.getElementById('stat-siswa').innerText = stats.total_siswa;
            document.getElementById('stat-kelas').innerText = stats.total_kelas;
            document.getElementById('stat-mapel').innerText = stats.total_mapel;
            document.getElementById('stat-rata').innerText = stats.rata_rata_keseluruhan;
        } catch (error) {
            document.getElementById('stat-siswa').innerHTML = '<span class="text-red-500 text-lg">Error</span>';
            document.getElementById('stat-kelas').innerHTML = '<span class="text-red-500 text-lg">Error</span>';
            document.getElementById('stat-mapel').innerHTML = '<span class="text-red-500 text-lg">Error</span>';
            document.getElementById('stat-rata').innerHTML = '<span class="text-red-500 text-lg">Error</span>';
            handleError(error); 
        }
    }
    document.addEventListener('DOMContentLoaded', loadDashboardData);
</script>
@endpush