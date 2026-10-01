<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    protected $table = 'orang_tua';

    protected $fillable = [
        'siswa_id',
        'nama_ayah',
        'nama_ibu',
        'alamat_orang_tua',
        'no_hp_orang_tua',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}