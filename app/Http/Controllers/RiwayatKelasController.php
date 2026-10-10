<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\RiwayatKelas;
use App\Models\TahunAjaran;
use App\Exports\RiwayatKelasExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RiwayatKelasController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaran = TahunAjaran::orderByDesc('status_aktif')
            ->orderBy('nama_tahun_ajaran', 'desc')
            ->get();

        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();

        $jenisRiwayat = RiwayatKelas::whereNotNull('keterangan')
            ->where('keterangan', '!=', '')
            ->distinct()
            ->orderBy('keterangan', 'asc')
            ->pluck('keterangan');

        [$tahunDari, $tahunSampai] = $this->resolveTahunAjaran($request);

        $query = RiwayatKelas::with([
            'siswa',
            'kelas',
            'tahunAjaran',
        ])
            ->join(
                'tahun_ajaran',
                'riwayat_kelas.tahun_ajaran_id',
                '=',
                'tahun_ajaran.id'
            )
            ->select('riwayat_kelas.*');

        if ($tahunDari && $tahunSampai) {
            $query->whereBetween(
                'tahun_ajaran.nama_tahun_ajaran',
                [
                    $tahunDari->nama_tahun_ajaran,
                    $tahunSampai->nama_tahun_ajaran,
                ]
            );
        }

        $query
            ->when($request->kelas_id, function ($query) use ($request) {
                $query->where(
                    'riwayat_kelas.kelas_id',
                    $request->kelas_id
                );
            })

            ->when($request->keterangan, function ($query) use ($request) {
                $query->where(
                    'riwayat_kelas.keterangan',
                    $request->keterangan
                );
            })

            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;

                $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('nik', 'like', '%' . $search . '%')
                        ->orWhere('nis', 'like', '%' . $search . '%')
                        ->orWhere('nisn', 'like', '%' . $search . '%');
                });
            });

        $riwayat = $query
            ->orderBy(
                'tahun_ajaran.nama_tahun_ajaran',
                'desc'
            )
            ->orderBy(
                'riwayat_kelas.kelas_id',
                'asc'
            )
            ->get();

        $riwayatPerTahun = $riwayat->groupBy(function ($item) {
            return $item->tahunAjaran
                ? $item->tahunAjaran->nama_tahun_ajaran
                : 'Tanpa Tahun Ajaran';
        });

        return view('riwayat-kelas.index', compact(
            'tahunAjaran',
            'kelas',
            'jenisRiwayat',
            'riwayat',
            'riwayatPerTahun'
        ));
    }


    public function export(Request $request)
    {
        if (
            !$request->filled('tahun_dari_id') &&
            !$request->filled('tahun_sampai_id')
        ) {
            return redirect()
                ->route(
                    'riwayat-kelas.index',
                    $request->query()
                )
                ->with(
                    'error',
                    'Pilih minimal satu tahun ajaran sebelum melakukan export.'
                );
        }

        [$tahunDari, $tahunSampai] = $this->resolveTahunAjaran($request);

        if (!$tahunDari || !$tahunSampai) {
            return redirect()
                ->route('riwayat-kelas.index')
                ->with(
                    'error',
                    'Tahun ajaran yang dipilih tidak valid.'
                );
        }

        if (
            $tahunDari->id === $tahunSampai->id
        ) {
            $namaFile =
                'riwayat_kelas_' .
                str_replace(
                    '/',
                    '-',
                    $tahunDari->nama_tahun_ajaran
                ) .
                '.xlsx';
        } else {
            $namaFile =
                'riwayat_kelas_' .
                str_replace(
                    '/',
                    '-',
                    $tahunDari->nama_tahun_ajaran
                ) .
                '_sampai_' .
                str_replace(
                    '/',
                    '-',
                    $tahunSampai->nama_tahun_ajaran
                ) .
                '.xlsx';
        }

        return Excel::download(
            new RiwayatKelasExport(
                $tahunDari->nama_tahun_ajaran,
                $tahunSampai->nama_tahun_ajaran,
                $request->kelas_id,
                $request->keterangan,
                $request->search
            ),
            $namaFile
        );
    }


    private function resolveTahunAjaran(Request $request): array
    {
        $tahunDari = null;
        $tahunSampai = null;

        if ($request->filled('tahun_dari_id')) {
            $tahunDari = TahunAjaran::find(
                $request->tahun_dari_id
            );
        }

        if ($request->filled('tahun_sampai_id')) {
            $tahunSampai = TahunAjaran::find(
                $request->tahun_sampai_id
            );
        }

        /*
         * Kalau hanya satu tahun dipilih,
         * dianggap sebagai pencarian satu tahun ajaran.
         */
        if ($tahunDari && !$tahunSampai) {
            $tahunSampai = $tahunDari;
        }

        if (!$tahunDari && $tahunSampai) {
            $tahunDari = $tahunSampai;
        }

        /*
         * Jika admin terbalik memilih rentang,
         * sistem otomatis membaliknya.
         */
        if (
            $tahunDari &&
            $tahunSampai &&
            strcmp(
                $tahunDari->nama_tahun_ajaran,
                $tahunSampai->nama_tahun_ajaran
            ) > 0
        ) {
            $sementara = $tahunDari;
            $tahunDari = $tahunSampai;
            $tahunSampai = $sementara;
        }

        return [
            $tahunDari,
            $tahunSampai,
        ];
    }
}
