<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wali extends Model
{
    protected $table = 'wali';

    protected $fillable = [
        'siswa_id',
        'nama_wali',
        'alamat_wali',
        'no_hp_wali',
        'pekerjaan_wali',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}