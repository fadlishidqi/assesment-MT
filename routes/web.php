<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.index');
});

Route::get('/siswa', function () {
    return view('siswa.index');
});

Route::get('/laporan', function () {
    return view('laporan.index');
});

Route::get('/nilai', function () {
    return view('nilai.index');
});

Route::get('/kelas', function () {
    return view('kelas.index');
});