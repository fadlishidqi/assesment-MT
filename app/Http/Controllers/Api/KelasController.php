<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    // Mengambil semua data kelas (GET)
    public function index()
    {
        $kelas = Kelas::all();
        return response()->json([
            'success' => true,
            'message' => 'Daftar data Kelas',
            'data'    => $kelas
        ], 200);
    }

    // Menyimpan data kelas baru (POST)
    public function store(Request $request)
    {
        $request->validate(['nama_kelas' => 'required|string|max:255']);
        
        $kelas = Kelas::create(['nama_kelas' => $request->nama_kelas]);
        
        return response()->json([
            'success' => true, 
            'message' => 'Kelas berhasil ditambahkan!',
            'data'    => $kelas
        ], 201);
    }

    // Mengupdate data kelas (PUT/PATCH)
    public function update(Request $request, $id)
    {
        $request->validate(['nama_kelas' => 'required|string|max:255']);
        
        $kelas = Kelas::find($id);
        if (!$kelas) {
            return response()->json(['success' => false, 'message' => 'Kelas tidak ditemukan'], 404);
        }

        $kelas->update(['nama_kelas' => $request->nama_kelas]);
        
        return response()->json([
            'success' => true, 
            'message' => 'Kelas berhasil diupdate!',
            'data'    => $kelas
        ], 200);
    }

    // Menghapus data kelas (DELETE)
    public function destroy($id)
    {
        $kelas = Kelas::find($id);
        if (!$kelas) {
            return response()->json(['success' => false, 'message' => 'Kelas tidak ditemukan'], 404);
        }

        $kelas->delete();
        
        return response()->json([
            'success' => true, 
            'message' => 'Kelas berhasil dihapus!'
        ], 200);
    }
}