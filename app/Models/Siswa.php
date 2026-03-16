<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'tbl_siswa';
    protected $primaryKey = 'Nid_siswa';
    protected $fillable = ['Nnis', 'Vnama', 'Nid_kelas'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'Nid_kelas', 'Nid_kelas');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'Nid_siswa', 'Nid_siswa');
    }
}