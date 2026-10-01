<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::where('status_siswa', 'Aktif')->count();

        $totalLakiLaki = Siswa::where('status_siswa', 'Aktif')
            ->where('jenis_kelamin', 'Laki-laki')
            ->count();

        $totalPerempuan = Siswa::where('status_siswa', 'Aktif')
            ->where('jenis_kelamin', 'Perempuan')
            ->count();

        $statistikAgama = Siswa::where('status_siswa', 'Aktif')
            ->whereNotNull('agama')
            ->where('agama', '!=', '')
            ->select(
                'agama',
                DB::raw("
                    SUM(
                        CASE 
                            WHEN jenis_kelamin = 'Laki-laki' 
                            THEN 1 ELSE 0 
                        END
                    ) as laki_laki
                "),
                DB::raw("
                    SUM(
                        CASE 
                            WHEN jenis_kelamin = 'Perempuan' 
                            THEN 1 ELSE 0 
                        END
                    ) as perempuan
                "),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('agama')
            ->orderBy('agama', 'asc')
            ->get();

        $statistikAsalSekolah = Siswa::where('status_siswa', 'Aktif')
            ->whereNotNull('asal_sekolah')
            ->where('asal_sekolah', '!=', '')
            ->select(
                'asal_sekolah',
                DB::raw("
                    SUM(
                        CASE 
                            WHEN jenis_kelamin = 'Laki-laki' 
                            THEN 1 ELSE 0 
                        END
                    ) as laki_laki
                "),
                DB::raw("
                    SUM(
                        CASE 
                            WHEN jenis_kelamin = 'Perempuan' 
                            THEN 1 ELSE 0 
                        END
                    ) as perempuan
                "),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('asal_sekolah')
            ->orderBy('asal_sekolah', 'asc')
            ->get();

        $jumlahAgama = $statistikAgama->count();
        $jumlahAsalSekolah = $statistikAsalSekolah->count();

        return view('statistik.index', compact(
            'totalSiswa',
            'totalLakiLaki',
            'totalPerempuan',
            'statistikAgama',
            'statistikAsalSekolah',
            'jumlahAgama',
            'jumlahAsalSekolah'
        ));
    }
}
