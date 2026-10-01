<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    protected $table = 'mutasi';

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'jenis_mutasi',
        'tanggal_mutasi',
        'sekolah_asal_tujuan',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mutasi' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}