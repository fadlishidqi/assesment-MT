@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Laporan Nilai Siswa</h2>
        <p class="text-gray-500 text-sm mt-1">Rekapitulasi dan grafik distribusi predikat</p>
    </div>
    <button onclick="exportToCSV()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg font-medium text-sm shadow-sm transition flex items-center">
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
            <button onclick="loadDataLaporan()" class="w-full bg-secondary hover:bg-gray-800 text-white px-4 py-2.5 rounded-lg font-medium text-sm shadow-sm transition">
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
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeModalKelas()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 flex flex-col max-h-[85vh]">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <div>
                <h3 class="text-lg font-bold text-gray-900" id="titleKelas">Detail Kelas</h3>
                <p class="text-xs text-gray-500 mt-1" id="subtitleKelas">Data statistik nilai per kelas</p>
            </div>
            <button onclick="closeModalKelas()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto bg-gray-50">
            <div id="contentKelas" class="space-y-4">
                </div>
        </div>
        <div class="flex items-center justify-end p-4 border-t border-gray-100">
            <button onclick="closeModalKelas()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium text-sm">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let laporanData = [];
    let myChart = null;

    async function loadDataLaporan() {
        try {
            const tahun = document.getElementById('filterTahun').value;
            const semester = document.getElementById('filterSemester').value;
            const url = `/api/v1/nilai/laporan?tahun_ajaran=${tahun}&semester=${semester}`;

            const response = await fetch(url);
            if (!response.ok) throw response;
            
            const result = await response.json();
            laporanData = result.data;
            
            renderTable(laporanData);
            renderChart(laporanData);
        } catch (error) {
            document.getElementById('tbody-laporan').innerHTML = '<tr><td colspan="6" class="py-8 text-center text-sm text-red-500">Gagal memuat data</td></tr>';
            handleError(error);
        }
    }

    function renderTable(data) {
        const tbody = document.getElementById('tbody-laporan');
        tbody.innerHTML = '';
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="py-8 text-center text-sm text-gray-500">Tidak ada data</td></tr>';
            return;
        }

        data.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.className = "hover:bg-gray-50 transition";
            // Perhatikan link pada bagian Kelas: memanggil function showDetailKelas
            tr.innerHTML = `
                <td class="py-3 px-4 text-sm text-gray-900 text-center">${index + 1}</td>
                <td class="py-3 px-4 text-sm font-medium text-gray-900">${item.nis}</td>
                <td class="py-3 px-4 text-sm text-gray-700 font-semibold">${item.nama_siswa}</td>
                <td class="py-3 px-4 text-sm">
                    <button onclick="showDetailKelas(${item.id_kelas || 1}, '${item.kelas}')" class="text-primary hover:underline font-medium flex items-center">
                        ${item.kelas}
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </td>
                <td class="py-3 px-4 text-sm text-gray-900 font-bold text-center">${parseFloat(item.rata_rata_keseluruhan).toFixed(2)}</td>
                <td class="py-3 px-4 text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold ${getTailwindColorByPredikat(item.predikat)}">
                        ${item.predikat}
                    </span>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // FUNGSI IMPLEMENTASI ENDPOINT: /api/v1/laporan/kelas/{id_kelas}
    async function showDetailKelas(id, namaKelas) {
        const modal = document.getElementById('modalDetailKelas');
        const content = document.getElementById('contentKelas');
        
        document.getElementById('titleKelas').innerText = `Detail Nilai: ${namaKelas}`;
        content.innerHTML = '<div class="flex justify-center py-10"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div></div>';
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        try {
            const response = await fetch(`/api/v1/laporan/kelas/${id}`);
            if (!response.ok) throw response;
            const result = await response.json();
            const dataSiswa = Array.isArray(result.data) ? result.data : Object.values(result.data);

            if (dataSiswa.length === 0) {
                content.innerHTML = '<p class="text-center text-gray-500 py-10 text-sm">Belum ada data nilai di kelas ini.</p>';
                return;
            }

            let html = '<div class="grid grid-cols-1 gap-3">';
            dataSiswa.forEach(s => {
                html += `
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-gray-400 font-medium">${s.nis}</p>
                            <p class="text-sm font-bold text-gray-800">${s.nama_siswa}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 uppercase tracking-tighter">Rata-rata</p>
                            <p class="text-lg font-black text-primary">${parseFloat(s.rata_rata).toFixed(2)}</p>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            content.innerHTML = html;

        } catch (error) {
            content.innerHTML = '<p class="text-center text-red-500 py-10 text-sm">Gagal memuat data kelas.</p>';
            handleError(error);
        }
    }

    function closeModalKelas() {
        const modal = document.getElementById('modalDetailKelas');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function getTailwindColorByPredikat(predikat) {
        if (predikat === 'A') return 'bg-emerald-100 text-emerald-800';
        if (predikat === 'B') return 'bg-blue-100 text-blue-800';
        if (predikat === 'C') return 'bg-amber-100 text-amber-800';
        return 'bg-rose-100 text-rose-800'; 
    }

    function renderChart(data) {
        const counts = { 'A': 0, 'B': 0, 'C': 0, 'D': 0 };
        data.forEach(item => { if (counts[item.predikat] !== undefined) counts[item.predikat]++; });

        if (myChart) myChart.destroy();

        const ctx = document.getElementById('predikatChart').getContext('2d');
        myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Predikat A', 'Predikat B', 'Predikat C', 'Predikat D'],
                datasets: [{
                    data: [counts['A'], counts['B'], counts['C'], counts['D']],
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#f43f5e'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } }
            }
        });
    }

    function exportToCSV() {
        if (laporanData.length === 0) return alert("Tidak ada data untuk diexport");
        let csvContent = "No,NIS,Nama Siswa,Kelas,Rata-rata Nilai,Predikat\n";
        laporanData.forEach((item, index) => {
            csvContent += `${index + 1},${item.nis},"${item.nama_siswa}",${item.kelas},${parseFloat(item.rata_rata_keseluruhan).toFixed(2)},${item.predikat}\n`;
        });
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "Laporan_Nilai_Siswa.csv";
        link.click();
    }

    document.addEventListener('DOMContentLoaded', loadDataLaporan);
</script>
@endpush