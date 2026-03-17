window.laporanData = [];
window.myChart = null;

window.loadDataLaporan = async function() {
    try {
        const tahun = document.getElementById('filterTahun').value;
        const semester = document.getElementById('filterSemester').value;
        const url = `/api/v1/nilai/laporan?tahun_ajaran=${encodeURIComponent(tahun)}&semester=${encodeURIComponent(semester)}`;

        const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || "Terjadi kesalahan di server");
        }
        
        const result = await response.json();
        window.laporanData = result.data || [];
        
        renderTable(window.laporanData);
        renderChart(window.laporanData);
    } catch (error) {
        document.getElementById('tbody-laporan').innerHTML = `<tr><td colspan="6" class="py-8 text-center text-sm text-red-500">Gagal memuat data: ${error.message}</td></tr>`;
        console.error("Laporan API Error:", error);
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
        const idKelas = item.id_kelas || 1;
        const tr = document.createElement('tr');
        tr.className = "hover:bg-gray-50 transition";
        tr.innerHTML = `
            <td class="py-3 px-4 text-sm text-gray-900 text-center">${index + 1}</td>
            <td class="py-3 px-4 text-sm font-medium text-gray-900">${item.nis}</td>
            <td class="py-3 px-4 text-sm text-gray-700 font-semibold">${item.nama_siswa}</td>
            <td class="py-3 px-4 text-sm">
                <button onclick="window.showDetailKelas(${idKelas}, '${item.kelas}')" class="text-primary hover:underline font-medium flex items-center">
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

window.showDetailKelas = async function(id, namaKelas) {
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
        
        const dataSiswa = result.data.daftar_siswa || [];

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
                        <p class="text-lg font-black text-primary">${parseFloat(s.rata_rata_keseluruhan).toFixed(2)}</p>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        content.innerHTML = html;

    } catch (error) {
        content.innerHTML = '<p class="text-center text-red-500 py-10 text-sm">Gagal memuat data kelas.</p>';
        console.error("Detail Kelas Error:", error); 
    }
}

window.closeModalKelas = function() {
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

    if (window.myChart) window.myChart.destroy();

    const ctx = document.getElementById('predikatChart').getContext('2d');
    window.myChart = new Chart(ctx, {
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

window.exportToCSV = function() {
    if (window.laporanData.length === 0) return alert("Tidak ada data untuk diexport");
    let csvContent = "No,NIS,Nama Siswa,Kelas,Rata-rata Nilai,Predikat\n";
    window.laporanData.forEach((item, index) => {
        csvContent += `${index + 1},${item.nis},"${item.nama_siswa}",${item.kelas},${parseFloat(item.rata_rata_keseluruhan).toFixed(2)},${item.predikat}\n`;
    });
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "Laporan_Nilai_Siswa.csv";
    link.click();
}

document.addEventListener('DOMContentLoaded', window.loadDataLaporan);