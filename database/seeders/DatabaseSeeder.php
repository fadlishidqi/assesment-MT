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

        $mapel1 = Mapel::create(['Vnama_mapel' => 'Matematika']);
        $mapel2 = Mapel::create(['Vnama_mapel' => 'Fisika']);
        $mapel3 = Mapel::create(['Vnama_mapel' => 'Bahasa Indonesia']);

        $siswaIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $siswa = Siswa::create([
                'Nnis' => 1000 + $i,
                'Vnama' => 'Siswa Dummy ' . $i,
                'Nid_kelas' => $kelas->Nid_kelas,
            ]);
            $siswaIds[] = $siswa->Nid_siswa;
        }

        for ($i = 0; $i < 10; $i++) {
            Nilai::create([
                'Nid_siswa' => $siswaIds[array_rand($siswaIds)],
                'Nid_mapel' => rand(1, 3),
                'Nuh' => rand(60, 100),
                'Nuts' => rand(60, 100),
                'Nuas' => rand(60, 100),
            ]);
        }
    }
}