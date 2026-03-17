async function loadDashboardData() {
    try {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 8000); 

        const response = await fetch('/api/v1/dashboard/stats', {
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
        console.error(error); 
    }
}

document.addEventListener('DOMContentLoaded', loadDashboardData);