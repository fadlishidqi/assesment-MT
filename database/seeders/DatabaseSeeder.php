<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Mapel;
use App\Models\Nilai;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = Kelas::create(['Vnama_kelas' => '12 IPA 1']);

        $mapels = [
            Mapel::create(['Vnama_mapel' => 'Matematika']),
            Mapel::create(['Vnama_mapel' => 'Fisika']),
            Mapel::create(['Vnama_mapel' => 'Bahasa Indonesia']),
        ];

        for ($i = 1; $i <= 10; $i++) {
            $siswa = Siswa::create([
                'Nnis' => 1000 + $i,
                'Vnama' => 'Siswa Dummy ' . $i,
                'Nid_kelas' => $kelas->Nid_kelas,
            ]);

            foreach ($mapels as $mapel) {
                Nilai::create([
                    'Nid_siswa' => $siswa->Nid_siswa,
                    'Nid_mapel' => $mapel->Nid_mapel,
                    'Vtahun_ajaran' => '2025/2026', 
                    'Vsemester' => 'Ganjil',
                    
                    'Nuh' => rand(60, 100),
                    'Nuts' => rand(60, 100),
                    'Nuas' => rand(60, 100),
                ]);
            }
        }
    }
}