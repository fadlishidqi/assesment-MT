<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/siswa', function () {
    return view('siswa');
});

Route::get('/laporan', function () {
    return view('laporan');
});

Route::get('/nilai', function () {
    return view('nilai');
});