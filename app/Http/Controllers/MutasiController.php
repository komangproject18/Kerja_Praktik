<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MutasiController extends Controller
{
    public function index(Request $request)
    {
        $mutasi = Mutasi::with(['siswa', 'kelas'])
            ->when($request->jenis, function ($query) use ($request) {
                $query->where('jenis_mutasi', $request->jenis);
            })
            ->orderBy('tanggal_mutasi', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('mutasi.index', compact('mutasi'));
    }

    public function createMasuk()
    {
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();

        return view('mutasi.masuk', compact('kelas'));
    }

    public function storeMasuk(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'nik' => 'required|string|unique:siswa,nik',
            'nis' => 'nullable|string|max:50|unique:siswa,nis',
            'nisn' => 'nullable|string|max:50|unique:siswa,nisn',

            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',

            'kelas_id' => 'required|exists:kelas,id',
            'tanggal_mutasi' => 'required|date',

            'sekolah_asal_tujuan' => 'required|string|max:150',
            'keterangan' => 'nullable|string',

            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama.required' => 'Nama siswa wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK sudah digunakan oleh siswa lain.',
            'nis.unique' => 'NIS sudah digunakan.',
            'nisn.unique' => 'NISN sudah digunakan.',
            'kelas_id.required' => 'Kelas tujuan wajib dipilih.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',
            'tanggal_mutasi.required' => 'Tanggal mutasi wajib diisi.',
            'sekolah_asal_tujuan.required' => 'Sekolah asal wajib diisi.',
        ]);

        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('siswa', 'public');
        }

        try {
            DB::transaction(function () use ($request, $fotoPath) {

                $siswa = Siswa::create([
                    'nama' => $request->nama,
                    'nik' => $request->nik,
                    'foto' => $fotoPath,

                    'nis' => $request->nis,
                    'nisn' => $request->nisn,

                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'jenis_kelamin' => $request->jenis_kelamin,

                    'asal_sekolah' => $request->sekolah_asal_tujuan,

                    'kelas_id' => $request->kelas_id,
                    'tanggal_diterima' => $request->tanggal_mutasi,

                    'status_siswa' => 'Aktif',
                ]);

                Mutasi::create([
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $request->kelas_id,
                    'jenis_mutasi' => 'Masuk',
                    'tanggal_mutasi' => $request->tanggal_mutasi,
                    'sekolah_asal_tujuan' => $request->sekolah_asal_tujuan,
                    'keterangan' => $request->keterangan,
                ]);
            });
        } catch (\Throwable $e) {

            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }

            throw $e;
        }

        return redirect()
            ->route('mutasi.index')
            ->with('success', 'Siswa mutasi masuk berhasil ditambahkan.');
    }

    public function createKeluar()
    {
        $siswa = Siswa::with('kelas')
            ->where('status_siswa', 'Aktif')
            ->orderBy('nama', 'asc')
            ->get();

        return view('mutasi.keluar', compact('siswa'));
    }

    public function storeKeluar(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tanggal_mutasi' => 'required|date',
            'sekolah_asal_tujuan' => 'nullable|string|max:150',
            'keterangan' => 'nullable|string',
        ], [
            'siswa_id.required' => 'Siswa wajib dipilih.',
            'siswa_id.exists' => 'Data siswa tidak ditemukan.',
            'tanggal_mutasi.required' => 'Tanggal mutasi wajib diisi.',
            'tanggal_mutasi.date' => 'Tanggal mutasi tidak valid.',
        ]);

        DB::transaction(function () use ($request) {
            $siswa = Siswa::where('id', $request->siswa_id)
                ->where('status_siswa', 'Aktif')
                ->firstOrFail();

            $kelasTerakhir = $siswa->kelas_id;

            Mutasi::create([
                'siswa_id' => $siswa->id,
                'kelas_id' => $kelasTerakhir,
                'jenis_mutasi' => 'Keluar',
                'tanggal_mutasi' => $request->tanggal_mutasi,
                'sekolah_asal_tujuan' => $request->sekolah_asal_tujuan,
                'keterangan' => $request->keterangan,
            ]);

            $siswa->update([
                'status_siswa' => 'Mutasi Keluar',
                'kelas_id' => null,
            ]);
        });

        return redirect()
            ->route('mutasi.index')
            ->with('success', 'Siswa berhasil diproses sebagai mutasi keluar.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'integer|exists:mutasi,id',
        ], [
            'selected_ids.required' => 'Pilih minimal satu riwayat mutasi.',
            'selected_ids.min' => 'Pilih minimal satu riwayat mutasi.',
            'selected_ids.*.exists' => 'Riwayat mutasi yang dipilih tidak valid.',
        ]);

        $jumlahData = count($request->selected_ids);

        Mutasi::whereIn('id', $request->selected_ids)->delete();

        return redirect()
            ->route('mutasi.index')
            ->with('success', $jumlahData . ' riwayat mutasi berhasil dihapus.');
    }
}
