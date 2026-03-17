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
    @vite('resources/js/dashboard.js')
@endpush