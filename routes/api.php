<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\NilaiController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MapelController;
use App\Http\Controllers\Api\KelasController;

Route::prefix('v1')->group(function () {

    // Siswa
    Route::prefix('siswa')->group(function () {
        Route::get('/', [SiswaController::class, 'index']);
        Route::post('/', [SiswaController::class, 'store']);
        Route::get('/{id}', [SiswaController::class, 'show']);
        Route::put('/{id}', [SiswaController::class, 'update']);
        Route::delete('/{id}', [SiswaController::class, 'destroy']);
    });

    // Laporan
    Route::get('/nilai/laporan', [LaporanController::class, 'index']);
    Route::get('/laporan/kelas/{id_kelas}', [LaporanController::class, 'kelas']);

    // Nilai
    Route::prefix('nilai')->group(function () {
        Route::get('/', [NilaiController::class, 'index']);
        Route::post('/', [NilaiController::class, 'store']);
        Route::put('/{id}', [NilaiController::class, 'update']);
        Route::delete('/{id}', [NilaiController::class, 'destroy']);
    });

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Mapel
    Route::get('/mapel', [MapelController::class, 'index']);

    // Kelas
    Route::get('/kelas', [KelasController::class, 'index']);
    Route::post('/kelas', [KelasController::class, 'store']);

});