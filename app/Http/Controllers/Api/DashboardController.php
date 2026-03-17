<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;

class DashboardController extends Controller
{
    public function stats()
    {
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
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}