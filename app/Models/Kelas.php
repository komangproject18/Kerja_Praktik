<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'wali_kelas',
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    public function mutasi()
    {
        return $this->hasMany(Mutasi::class, 'kelas_id');
    }
}
