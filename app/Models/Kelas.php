<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'tbl_kelas';
    protected $primaryKey = 'Nid_kelas';
    protected $fillable = ['Vnama_kelas'];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'Nid_kelas', 'Nid_kelas');
    }
}
