<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nilai;
use Illuminate\Support\Facades\Validator;
use Exception;

class NilaiController extends Controller
{
    // GET /api/v1/nilai
    public function index()
    {
        try {
            $nilai = Nilai::with(['siswa', 'mapel'])->get();
            return response()->json(['status' => 'success', 'data' => $nilai], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // POST /api/v1/nilai
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'Nid_siswa' => 'required|exists:tbl_siswa,Nid_siswa',
                'Nid_mapel' => 'required|exists:tbl_mapel,Nid_mapel',
                'Nuh' => 'required|numeric|min:0|max:100',
                'Nuts' => 'required|numeric|min:0|max:100',
                'Nuas' => 'required|numeric|min:0|max:100',
                'Vtahun_ajaran' => 'required|string',
                'Vsemester' => 'required|in:Ganjil,Genap',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => 'Validasi Gagal', 'errors' => $validator->errors()], 400);
            }

            $nilai = Nilai::create($request->all());
            return response()->json(['status' => 'success', 'message' => 'Data nilai berhasil ditambahkan', 'data' => $nilai], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // PUT /api/v1/nilai/{id}
    public function update(Request $request, $id)
    {
        try {
            $nilai = Nilai::find($id);
            if (!$nilai) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);

            $validator = Validator::make($request->all(), [
                'Nid_siswa' => 'required|exists:tbl_siswa,Nid_siswa',
                'Nid_mapel' => 'required|exists:tbl_mapel,Nid_mapel',
                'Nuh' => 'required|numeric|min:0|max:100',
                'Nuts' => 'required|numeric|min:0|max:100',
                'Nuas' => 'required|numeric|min:0|max:100',
                'Vtahun_ajaran' => 'required|string',
                'Vsemester' => 'required|in:Ganjil,Genap',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => 'Validasi Gagal', 'errors' => $validator->errors()], 400);
            }

            $nilai->update($request->all());
            return response()->json(['status' => 'success', 'message' => 'Data nilai berhasil diupdate', 'data' => $nilai], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // DELETE /api/v1/nilai/{id}
    public function destroy($id)
    {
        try {
            $nilai = Nilai::find($id);
            if (!$nilai) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);

            $nilai->delete();
            return response()->json(['status' => 'success', 'message' => 'Data nilai berhasil dihapus'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}