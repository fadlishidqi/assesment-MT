<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use Exception;

class LaporanController extends Controller
{
    // GET /api/v1/nilai/laporan
    public function index(Request $request)
    {
        try {
            $tahun = $request->query('tahun_ajaran');
            $semester = $request->query('semester');

            $siswas = Siswa::with(['kelas', 'nilai' => function($query) use ($tahun, $semester) {
                if ($tahun) $query->where('Vtahun_ajaran', $tahun);
                if ($semester) $query->where('Vsemester', $semester);
            }, 'nilai.mapel'])->get();

            $laporan = [];

            foreach ($siswas as $siswa) {
                if ($siswa->nilai->isEmpty() && ($tahun || $semester)) {
                    continue; 
                }

                $sp_result = DB::select('CALL sp_hitung_rata_rata_siswa(?)', [$siswa->Nid_siswa]);
                $kalkulasi = $sp_result[0] ?? null;

                $detail_nilai = [];
                foreach ($siswa->nilai as $nilai) {
                    $detail_nilai[] = [
                        'nama_mapel' => $nilai->mapel->Vnama_mapel ?? '-',
                        'uh' => $nilai->Nuh,
                        'uts' => $nilai->Nuts,
                        'uas' => $nilai->Nuas,
                        'tahun_ajaran' => $nilai->Vtahun_ajaran,
                        'semester' => $nilai->Vsemester
                    ];
                }

                $laporan[] = [
                    'nama_siswa' => $siswa->Vnama,
                    'nis' => $siswa->Nnis,
                    'kelas' => $siswa->kelas->Vnama_kelas ?? '-',
                    'id_kelas' => $siswa->Nid_kelas,
                    'detail_nilai' => $detail_nilai,
                    'rata_rata_keseluruhan' => $kalkulasi?->rata_rata ?? 0,
                    'total_nilai' => $kalkulasi?->total_nilai ?? 0,
                    'predikat' => $kalkulasi?->predikat ?? '-',
                ];
            }

            return response()->json(['status' => 'success', 'data' => $laporan], 200);

        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // GET /api/v1/laporan/kelas/{id_kelas}
    public function kelas($id_kelas)
    {
        try {
            $kelas = Kelas::find($id_kelas);
            if (!$kelas) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Not Found: Data kelas tidak ditemukan'
                ], 404);
            }

            $siswas = Siswa::where('Nid_kelas', $id_kelas)->get();
            $laporan_siswa = [];

            foreach ($siswas as $siswa) {
                $sp_result = DB::select('CALL sp_hitung_rata_rata_siswa(?)', [$siswa->Nid_siswa]);
                $kalkulasi = $sp_result[0] ?? null;

                $laporan_siswa[] = [
                    'nis' => $siswa->Nnis,
                    'nama_siswa' => $siswa->Vnama,
                    'rata_rata_keseluruhan' => $kalkulasi?->rata_rata ?? 0,
                    'predikat' => $kalkulasi?->predikat ?? '-',
                ];
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'kelas' => $kelas->Vnama_kelas,
                    'daftar_siswa' => $laporan_siswa
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
}