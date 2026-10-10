<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaran = TahunAjaran::orderBy(
            'nama_tahun_ajaran',
            'desc'
        )->get();

        $alumni = Siswa::with([
            'tahunLulus',
            'riwayatKelasTerakhir.kelas',
        ])
            ->where('status_siswa', 'Alumni')

            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('nik', 'like', '%' . $search . '%')
                        ->orWhere('nis', 'like', '%' . $search . '%')
                        ->orWhere('nisn', 'like', '%' . $search . '%');
                });
            })

            ->when(
                $request->tahun_lulus_id,
                function ($query) use ($request) {
                    $query->where(
                        'tahun_lulus_id',
                        $request->tahun_lulus_id
                    );
                }
            )

            ->orderByDesc('tahun_lulus_id')
            ->orderBy('nama', 'asc')
            ->get();

        $alumniPerTahun = $alumni->groupBy(function ($item) {
            return $item->tahun_lulus_id ?? 'tanpa_tahun';
        });

        return view('alumni.index', compact(
            'alumni',
            'alumniPerTahun',
            'tahunAjaran'
        ));
    }
}
