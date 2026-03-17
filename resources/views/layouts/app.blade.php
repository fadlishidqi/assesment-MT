<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Manajemen Data Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#4f46e5', secondary: '#1e293b' }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">
    <nav class="bg-white shadow-sm border-b border-gray-100 mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-xl font-bold text-primary">SekolahKu</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-500 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition">Dashboard</a>
                    <a href="/siswa" class="text-gray-500 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition">Manajemen Siswa</a>
                    <a href="/laporan" class="text-gray-500 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition">Laporan Nilai</a>
                    <a href="/nilai" class="text-gray-500 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition">Manajemen Nilai</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        @yield('content')
    </main>

    <div id="customConfirmModal" class="hidden fixed inset-0 z-50 items-center justify-center">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity"></div>
        <div class="relative bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4 transform transition-all">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-900" id="confirmTitle">Konfirmasi</h3>
                <p class="text-sm text-gray-500 mt-2" id="confirmMessage">Apakah Anda yakin?</p>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" id="btnCancel" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-sm">Batal</button>
                <button type="button" id="btnYes" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm shadow-sm">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    @vite('resources/js/utils.js')
    @stack('scripts')
</body>
</html>