<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Exports\SiswaExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function index()
    {
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();

        return view('export.index', compact('kelas'));
    }

    public function siswa(Request $request)
    {
        $kelasId = $request->kelas_id;

        $namaFile = 'data-siswa.xlsx';

        if ($kelasId) {
            $kelas = Kelas::find($kelasId);

            if ($kelas) {
                $namaKelas = str_replace(' ', '-', strtolower($kelas->nama_kelas));
                $namaFile = 'data-siswa-' . $namaKelas . '.xlsx';
            }
        }

        return Excel::download(new SiswaExport($kelasId), $namaFile);
    }
}