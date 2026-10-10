<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\RiwayatKelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::withCount('siswa')
            ->orderBy('nama_kelas', 'asc')
            ->get();

        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|min:2|max:50|unique:kelas,nama_kelas',
            'wali_kelas' => 'nullable|string|min:3|max:150',
        ], [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'nama_kelas.min' => 'Nama kelas minimal 2 karakter.',
            'nama_kelas.unique' => 'Nama kelas sudah ada.',
            'nama_kelas.max' => 'Nama kelas maksimal 50 karakter.',
            'wali_kelas.min' => 'Nama wali kelas minimal 3 karakter.',
            'wali_kelas.max' => 'Nama wali kelas maksimal 150 karakter.',
        ]);

        try {
            Kelas::create([
                'nama_kelas' => $request->nama_kelas,
                'wali_kelas' => $request->wali_kelas,
            ]);

            return redirect()
                ->route('kelas.index')
                ->with('success', 'Data kelas berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data kelas: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $kelas = Kelas::with([
            'siswa' => function ($query) {
                $query->where('status_siswa', 'Aktif')
                    ->orderBy('nama', 'asc');
            }
        ])->findOrFail($id);

        $semuaKelas = Kelas::orderBy('nama_kelas', 'asc')->get();

        $tahunAjaranAktif = TahunAjaran::where('status_aktif', true)->first();

        return view('kelas.show', compact(
            'kelas',
            'semuaKelas',
            'tahunAjaranAktif'
        ));
    }

    public function naikKelas(Request $request, $id)
    {
        $kelasAsal = Kelas::findOrFail($id);

        $request->validate([
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'integer|exists:siswa,id',
            'kelas_tujuan_id' => 'required|exists:kelas,id',
        ], [
            'selected_ids.required' => 'Pilih minimal satu siswa.',
            'selected_ids.min' => 'Pilih minimal satu siswa.',
            'kelas_tujuan_id.required' => 'Kelas tujuan wajib dipilih.',
            'kelas_tujuan_id.exists' => 'Kelas tujuan tidak valid.',
        ]);

        if ((int) $request->kelas_tujuan_id === (int) $kelasAsal->id) {
            return back()->with('error', 'Kelas tujuan tidak boleh sama dengan kelas asal.');
        }

        $tahunAjaranAktif = TahunAjaran::where('status_aktif', true)->first();

        if (!$tahunAjaranAktif) {
            return back()->with(
                'error',
                'Belum ada tahun ajaran aktif. Aktifkan tahun ajaran terlebih dahulu.'
            );
        }

        $kelasTujuan = Kelas::findOrFail($request->kelas_tujuan_id);

        $siswa = Siswa::whereIn('id', $request->selected_ids)
            ->where('kelas_id', $kelasAsal->id)
            ->where('status_siswa', 'Aktif')
            ->get();

        if ($siswa->isEmpty()) {
            return back()->with('error', 'Tidak ada siswa yang dapat diproses.');
        }

        DB::transaction(function () use (
            $siswa,
            $kelasTujuan,
            $tahunAjaranAktif
        ) {
            foreach ($siswa as $item) {

                $item->update([
                    'kelas_id' => $kelasTujuan->id,
                ]);

                RiwayatKelas::updateOrCreate(
                    [
                        'siswa_id' => $item->id,
                        'kelas_id' => $kelasTujuan->id,
                        'tahun_ajaran_id' => $tahunAjaranAktif->id,
                    ],
                    [
                        'keterangan' => 'Naik Kelas',
                    ]
                );
            }
        });

        return redirect()
            ->route('kelas.show', $kelasAsal->id)
            ->with(
                'success',
                $siswa->count() .
                    ' siswa berhasil dinaikkan ke kelas ' .
                    $kelasTujuan->nama_kelas .
                    ' pada tahun ajaran ' .
                    $tahunAjaranAktif->nama_tahun_ajaran .
                    '.'
            );
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);

        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas' => [
                'required',
                'string',
                'min:2',
                'max:50',
                Rule::unique('kelas', 'nama_kelas')->ignore($kelas->id),
            ],
            'wali_kelas' => 'nullable|string|min:3|max:150',
        ], [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'nama_kelas.min' => 'Nama kelas minimal 2 karakter.',
            'nama_kelas.unique' => 'Nama kelas sudah ada.',
            'nama_kelas.max' => 'Nama kelas maksimal 50 karakter.',
            'wali_kelas.min' => 'Nama wali kelas minimal 3 karakter.',
            'wali_kelas.max' => 'Nama wali kelas maksimal 150 karakter.',
        ]);

        try {
            $kelas->update([
                'nama_kelas' => $request->nama_kelas,
                'wali_kelas' => $request->wali_kelas,
            ]);

            return redirect()
                ->route('kelas.index')
                ->with('success', 'Data kelas berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data kelas: ' . $e->getMessage());
        }
    }

    public function jadikanAlumni(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'integer|exists:siswa,id',
        ], [
            'selected_ids.required' => 'Pilih minimal satu siswa.',
            'selected_ids.min' => 'Pilih minimal satu siswa.',
        ]);

        $tahunAjaranAktif = TahunAjaran::where('status_aktif', true)->first();

        if (!$tahunAjaranAktif) {
            return back()->with(
                'error',
                'Belum ada tahun ajaran aktif. Aktifkan tahun ajaran terlebih dahulu.'
            );
        }

        $siswa = Siswa::whereIn('id', $request->selected_ids)
            ->where('kelas_id', $kelas->id)
            ->where('status_siswa', 'Aktif')
            ->get();

        if ($siswa->isEmpty()) {
            return back()->with(
                'error',
                'Tidak ada siswa yang dapat diproses menjadi alumni.'
            );
        }

        DB::transaction(function () use (
            $siswa,
            $kelas,
            $tahunAjaranAktif
        ) {
            foreach ($siswa as $item) {

                /*
             * Pastikan kelas terakhir siswa tetap tercatat
             * dalam riwayat sebelum kelas_id dikosongkan.
             */
                RiwayatKelas::firstOrCreate(
                    [
                        'siswa_id' => $item->id,
                        'kelas_id' => $kelas->id,
                        'tahun_ajaran_id' => $tahunAjaranAktif->id,
                    ],
                    [
                        'keterangan' => 'Lulus',
                    ]
                );

                $item->update([
                    'status_siswa' => 'Alumni',
                    'kelas_id' => null,
                    'tahun_lulus_id' => $tahunAjaranAktif->id,
                ]);
            }
        });

        return redirect()
            ->route('kelas.show', $kelas->id)
            ->with(
                'success',
                $siswa->count() .
                    ' siswa berhasil dijadikan alumni tahun ajaran ' .
                    $tahunAjaranAktif->nama_tahun_ajaran .
                    '.'
            );
    }

    public function destroy($id)
    {
        try {
            $kelas = Kelas::findOrFail($id);

            if ($kelas->siswa()->count() > 0) {
                return redirect()
                    ->route('kelas.index')
                    ->with('error', 'Kelas tidak bisa dihapus karena masih memiliki ' . $kelas->siswa()->count() . ' siswa.');
            }

            $kelas->delete();

            return redirect()
                ->route('kelas.index')
                ->with('success', 'Data kelas berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('kelas.index')
                ->with('error', 'Gagal menghapus data kelas: ' . $e->getMessage());
        }
    }
}
