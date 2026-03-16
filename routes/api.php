<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\LaporanController;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Support\Facades\DB;

Route::prefix('v1')->group(function () {
    
    // Endpoint Siswa
    Route::get('/siswa', [SiswaController::class, 'index']);
    Route::post('/siswa', [SiswaController::class, 'store']);
    Route::get('/siswa/{id}', [SiswaController::class, 'show']);
    Route::put('/siswa/{id}', [SiswaController::class, 'update']);
    Route::delete('/siswa/{id}', [SiswaController::class, 'destroy']);

    // Endpoint Nilai 
    Route::get('/nilai/laporan', [LaporanController::class, 'index']); 
    Route::get('/laporan/kelas/{id_kelas}', [LaporanController::class, 'kelas']);

    Route::get('/dashboard-stats', function () {
        try {
            $rata_rata = DB::table('tbl_nilai')
                ->selectRaw('AVG((Nuh + Nuts + Nuas) / 3) as grand_average')
                ->first();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_siswa' => Siswa::count(),
                    'total_kelas' => Kelas::count(),
                    'total_mapel' => Mapel::count(),
                    'rata_rata_keseluruhan' => round($rata_rata->grand_average ?? 0, 2)
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    });
});