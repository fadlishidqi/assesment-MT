<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;

class KelasController extends Controller
{
    public function index()
    {
        try {
            return response()->json(['status' => 'success', 'data' => Kelas::all()], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $namaKelas = $request->input('Vnama_kelas') ?? $request->input('nama_kelas');

            if (empty($namaKelas)) {
                return response()->json(['status' => 'error', 'message' => 'Nama kelas wajib diisi!'], 400);
            }

            $kelas = new Kelas();
            $kelas->Vnama_kelas = $namaKelas;
            $kelas->save();

            return response()->json([
                'status' => 'success', 
                'message' => 'Kelas berhasil ditambahkan!', 
                'data' => $kelas
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}