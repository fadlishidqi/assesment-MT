<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'tbl_mapel';
    protected $primaryKey = 'Nid_mapel';
    protected $fillable = ['Vnama_mapel'];

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'Nid_mapel', 'Nid_mapel');
    }
}