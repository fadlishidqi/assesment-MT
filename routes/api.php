<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\NilaiController;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;

Route::prefix('v1')->group(function () {

    // --- ENDPOINT SISWA ---
    Route::prefix('siswa')->group(function () {
        Route::get('/', [SiswaController::class, 'index']);
        Route::post('/', [SiswaController::class, 'store']);
        Route::get('/{id}', [SiswaController::class, 'show']);
        Route::put('/{id}', [SiswaController::class, 'update']);
        Route::delete('/{id}', [SiswaController::class, 'destroy']);
    });

    // --- ENDPOINT LAPORAN (Diperbaiki agar URL-nya sesuai dengan frontend) ---
    Route::get('/nilai/laporan', [LaporanController::class, 'index']);
    Route::get('/laporan/kelas/{id_kelas}', [LaporanController::class, 'kelas']);

    // --- ENDPOINT CRUD NILAI ---
    Route::prefix('nilai')->group(function () {
        Route::get('/', [NilaiController::class, 'index']);
        Route::post('/', [NilaiController::class, 'store']);
        Route::put('/{id}', [NilaiController::class, 'update']);
        Route::delete('/{id}', [NilaiController::class, 'destroy']);
    });

    // --- ENDPOINT DASHBOARD STATS ---
    Route::get('/dashboard/stats', function () {
        try {
            $rataRata = DB::table('tbl_nilai')
                ->selectRaw('AVG((Nuh + Nuts + Nuas) / 3) as grand_average')
                ->value('grand_average');

            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_siswa' => Siswa::count(),
                    'total_kelas' => Kelas::count(),
                    'total_mapel' => Mapel::count(),
                    'rata_rata_keseluruhan' => round($rataRata ?? 0, 2)
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    });

    // --- ENDPOINT KELAS (Daftar Kelas untuk Dropdown) ---
    Route::get('/kelas', function () {
        try {
            return response()->json([
                'status' => 'success',
                'data' => Kelas::all()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    });

    // --- ENDPOINT MAPEL (Daftar Mata Pelajaran untuk Dropdown Nilai) ---
    Route::get('/mapel', function () {
        try {
            return response()->json([
                'status' => 'success',
                'data' => Mapel::all()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    });

});