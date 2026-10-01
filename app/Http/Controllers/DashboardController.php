<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::where('status_siswa', 'Aktif')->count();
        $totalKelas = Kelas::count();

        $totalLakiLaki = Siswa::where('status_siswa', 'Aktif')
            ->where('jenis_kelamin', 'Laki-laki')
            ->count();
        $totalPerempuan = Siswa::where('status_siswa', 'Aktif')
            ->where('jenis_kelamin', 'Perempuan')
            ->count();

        $siswaTerbaru = Siswa::with('kelas')
            ->where('status_siswa', 'Aktif')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalSiswa',
            'totalKelas',
            'totalLakiLaki',
            'totalPerempuan',
            'siswaTerbaru'
        ));
    }
}
