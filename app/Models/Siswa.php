<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nama',
        'nik',
        'foto',
        'status_siswa',
        'tahun_lulus_id',
        'nis',
        'nisn',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'status_anak',
        'anak_ke',
        'alamat',
        'no_hp',
        'asal_sekolah',
        'kelas_id',
        'tanggal_diterima',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function orangTua()
    {
        return $this->hasOne(OrangTua::class, 'siswa_id');
    }

    public function wali()
    {
        return $this->hasOne(Wali::class, 'siswa_id');
    }

    public function mutasi()
    {
        return $this->hasMany(Mutasi::class, 'siswa_id');
    }

    public function riwayatKelas()
    {
        return $this->hasMany(RiwayatKelas::class, 'siswa_id');
    }

    public function riwayatKelasTerakhir()
    {
        return $this->hasOne(RiwayatKelas::class, 'siswa_id')
            ->latestOfMany();
    }

    public function tahunLulus()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_lulus_id');
    }
}
