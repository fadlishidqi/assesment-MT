<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class SiswaController extends Controller
{
    // GET /api/siswa
    public function index()
    {
        try {
            $siswa = Siswa::with('kelas')->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $siswa
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST /api/siswa
    public function store(Request $request)
    {
        try {
            $request->validate([
                'Nnis' => 'required|numeric|unique:tbl_siswa,Nnis', // Tambahkan unique:tbl_siswa,Nnis
                'Vnama' => 'required|string|max:255',
                'Nid_kelas' => 'required|exists:tbl_kelas,Nid_kelas',
            ], [
                // Pesan error kustom agar lebih jelas dibaca pengguna
                'Nnis.unique' => 'NIS ini sudah terdaftar. Silakan gunakan NIS lain.',
                'Nnis.required' => 'NIS wajib diisi.',
                'Vnama.required' => 'Nama lengkap wajib diisi.',
                'Nid_kelas.required' => 'Kelas wajib dipilih.',
            ]);

            $siswa = Siswa::create([
                'Nnis' => $request->Nnis,
                'Vnama' => $request->Vnama,
                'Nid_kelas' => $request->Nid_kelas,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Siswa berhasil ditambahkan!',
                'data' => $siswa
            ], 201);
            
        // Gunakan ValidationException untuk menangkap error validasi dari $request->validate()
        } catch (\Illuminate\Validation\ValidationException $e) { 
             return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors() // Ini akan berisi detail errornya (misal: 'Nnis' => ['NIS ini sudah terdaftar...'])
            ], 422); // 422 Unprocessable Entity adalah kode HTTP standar untuk error validasi
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    // GET /api/siswa/{id}
    public function show($id)
    {
        try {
            $siswa = Siswa::with(['kelas', 'nilai.mapel'])->find($id);
            
            if (!$siswa) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Not Found: Data siswa tidak ditemukan'
                ], 404);
            }

            $sp_result = DB::select('CALL sp_hitung_rata_rata_siswa(?)', [$id]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'siswa' => $siswa,
                    'kalkulasi_nilai' => $sp_result[0] ?? null
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // PUT /api/siswa/{id}
    public function update(Request $request, $id)
    {
        try {
            $siswa = Siswa::find($id);
            if (!$siswa) {
                return response()->json(['status' => 'error', 'message' => 'Not Found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'Nnis' => 'required|numeric|unique:tbl_siswa,Nnis,' . $id . ',Nid_siswa',
                'Vnama' => 'required|string|max:100',
                'Nid_kelas' => 'required|exists:tbl_kelas,Nid_kelas'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => 'Bad Request', 'errors' => $validator->errors()], 400);
            }

            $siswa->update([
                'Nnis' => $request->Nnis,
                'Vnama' => $request->Vnama,
                'Nid_kelas' => $request->Nid_kelas
            ]);

            return response()->json(['status' => 'success', 'message' => 'Data siswa berhasil diupdate', 'data' => $siswa], 200);

        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    // DELETE /api/siswa/{id}
    public function destroy($id)
    {
        try {
            $siswa = Siswa::find($id);
            if (!$siswa) {
                return response()->json(['status' => 'error', 'message' => 'Not Found'], 404);
            }

            $siswa->delete();

            return response()->json(['status' => 'success', 'message' => 'Data siswa berhasil dihapus'], 200);

        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }
}