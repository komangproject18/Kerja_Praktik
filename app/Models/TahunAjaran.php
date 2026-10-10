<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'nama_tahun_ajaran',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function riwayatKelas()
    {
        return $this->hasMany(RiwayatKelas::class, 'tahun_ajaran_id');
    }
}
