<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'tbl_nilai';
    protected $primaryKey = 'Nid_nilai';
    protected $fillable = ['Nid_siswa', 'Nid_mapel', 'Nuh', 'Nuts', 'Nuas', 'Vtahun_ajaran', 'Vsemester'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'Nid_siswa', 'Nid_siswa');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'Nid_mapel', 'Nid_mapel');
    }
}