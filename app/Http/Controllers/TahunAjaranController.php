<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\RiwayatKelas;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderByDesc('status_aktif',)
            ->orderBy('nama_tahun_ajaran', 'asc')->get();

        return view('tahun-ajaran.index', compact('tahunAjaran'));
    }

    public function create()
    {
        return view('tahun-ajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tahun_ajaran' => 'required|string|max:20|unique:tahun_ajaran,nama_tahun_ajaran',
        ], [
            'nama_tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'nama_tahun_ajaran.unique' => 'Tahun ajaran tersebut sudah tersedia.',
        ]);

        TahunAjaran::create([
            'nama_tahun_ajaran' => $request->nama_tahun_ajaran,
            'status_aktif' => false,
        ]);

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        return view('tahun-ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, $id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        $request->validate([
            'nama_tahun_ajaran' =>
            'required|string|max:20|unique:tahun_ajaran,nama_tahun_ajaran,' . $tahunAjaran->id,
        ], [
            'nama_tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'nama_tahun_ajaran.unique' => 'Tahun ajaran tersebut sudah tersedia.',
        ]);

        $tahunAjaran->update([
            'nama_tahun_ajaran' => $request->nama_tahun_ajaran,
        ]);

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function activate($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        DB::transaction(function () use ($tahunAjaran) {
            TahunAjaran::query()->update([
                'status_aktif' => false,
            ]);

            $tahunAjaran->update([
                'status_aktif' => true,
            ]);
        });

        return redirect()
            ->route('tahun-ajaran.index')
            ->with(
                'success',
                'Tahun ajaran ' . $tahunAjaran->nama_tahun_ajaran . ' berhasil diaktifkan.'
            );
    }

    public function sinkronkan($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        if (!$tahunAjaran->status_aktif) {
            return redirect()
                ->route('tahun-ajaran.index')
                ->with('error', 'Sinkronisasi hanya dapat dilakukan pada tahun ajaran yang aktif.');
        }

        $siswa = Siswa::where('status_siswa', 'Aktif')
            ->whereNotNull('kelas_id')
            ->get();

        $jumlah = 0;

        foreach ($siswa as $item) {
            RiwayatKelas::updateOrCreate(
                [
                    'siswa_id' => $item->id,
                    'kelas_id' => $item->kelas_id,
                    'tahun_ajaran_id' => $tahunAjaran->id,
                ],
                [
                    'keterangan' => 'Data Awal',
                ]
            );

            $jumlah++;
        }

        return redirect()
            ->route('tahun-ajaran.index')
            ->with(
                'success',
                $jumlah . ' siswa berhasil disinkronkan ke tahun ajaran ' .
                    $tahunAjaran->nama_tahun_ajaran . '.'
            );
    }

    public function destroy($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        if ($tahunAjaran->status_aktif) {
            return redirect()
                ->route('tahun-ajaran.index')
                ->with('error', 'Tahun ajaran yang sedang aktif tidak dapat dihapus.');
        }

        $tahunAjaran->delete();

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
