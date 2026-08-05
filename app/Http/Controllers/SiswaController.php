<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Wali;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();

        $siswa = Siswa::with('kelas')
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->search . '%')
                        ->orWhere('nis', 'like', '%' . $request->search . '%')
                        ->orWhere('nisn', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->kelas_id, function ($query) use ($request) {
                $query->where('kelas_id', $request->kelas_id);
            })
            ->orderBy('nama', 'asc')
            ->get();

        return view('siswa.index', compact('siswa', 'kelas'));
    }

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv|max:5120',
    ], [
        'file.required' => 'File Excel wajib diunggah.',
        'file.mimes' => 'File harus berformat xlsx, xls, atau csv.',
        'file.max' => 'Ukuran file maksimal 5 MB.',
    ]);

    Excel::import(new SiswaImport, $request->file('file'));

    return redirect()
        ->route('siswa.index')
        ->with('success', 'Data siswa berhasil diimport dari Excel.');
}

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();

        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'nis' => 'nullable|string|max:50|unique:siswa,nis',
            'nisn' => 'nullable|string|max:50|unique:siswa,nisn',

            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|string|max:50',
            'status_anak' => 'nullable|string|max:50',
            'anak_ke' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'asal_sekolah' => 'nullable|string|max:150',
            'kelas_id' => 'nullable|exists:kelas,id',
            'tanggal_diterima' => 'nullable|date',

            'nama_ayah' => 'nullable|string|max:150',
            'nama_ibu' => 'nullable|string|max:150',
            'alamat_orang_tua' => 'nullable|string',
            'no_hp_orang_tua' => 'nullable|string|max:20',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'nullable|string|max:100',

            'nama_wali' => 'nullable|string|max:150',
            'alamat_wali' => 'nullable|string',
            'no_hp_wali' => 'nullable|string|max:20',
            'pekerjaan_wali' => 'nullable|string|max:100',
        ], [
            'nama.required' => 'Nama siswa wajib diisi.',
            'nis.unique' => 'NIS sudah digunakan.',
            'nisn.unique' => 'NISN sudah digunakan.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',
        ]);

        $adaOrangTua = $request->filled('nama_ayah') || $request->filled('nama_ibu');
        $adaWali = $request->filled('nama_wali');

        if (!$adaOrangTua && !$adaWali) {
            return back()
                ->withInput()
                ->withErrors([
                    'penanggung_jawab' => 'Data orang tua atau data wali wajib diisi minimal salah satu.',
                ]);
        }

        DB::transaction(function () use ($request, $adaOrangTua, $adaWali) {
            $siswa = Siswa::create([
                'nama' => $request->nama,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'status_anak' => $request->status_anak,
                'anak_ke' => $request->anak_ke,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'asal_sekolah' => $request->asal_sekolah,
                'kelas_id' => $request->kelas_id,
                'tanggal_diterima' => $request->tanggal_diterima,
            ]);

            if ($adaOrangTua) {
                OrangTua::create([
                    'siswa_id' => $siswa->id,
                    'nama_ayah' => $request->nama_ayah,
                    'nama_ibu' => $request->nama_ibu,
                    'alamat_orang_tua' => $request->alamat_orang_tua,
                    'no_hp_orang_tua' => $request->no_hp_orang_tua,
                    'pekerjaan_ayah' => $request->pekerjaan_ayah,
                    'pekerjaan_ibu' => $request->pekerjaan_ibu,
                ]);
            }

            if ($adaWali) {
                Wali::create([
                    'siswa_id' => $siswa->id,
                    'nama_wali' => $request->nama_wali,
                    'alamat_wali' => $request->alamat_wali,
                    'no_hp_wali' => $request->no_hp_wali,
                    'pekerjaan_wali' => $request->pekerjaan_wali,
                ]);
            }
        });

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }
    
    public function show($id)
{
    $siswa = Siswa::with(['kelas', 'orangTua', 'wali'])->findOrFail($id);

    return view('siswa.show', compact('siswa'));
}

public function edit($id)
{
    $siswa = Siswa::with(['orangTua', 'wali'])->findOrFail($id);
    $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();

    return view('siswa.edit', compact('siswa', 'kelas'));
}

public function update(Request $request, $id)
{
    $siswa = Siswa::with(['orangTua', 'wali'])->findOrFail($id);

    $request->validate([
        'nama' => 'required|string|max:150',
        'nis' => 'nullable|string|max:50|unique:siswa,nis,' . $siswa->id,
        'nisn' => 'nullable|string|max:50|unique:siswa,nisn,' . $siswa->id,

        'tempat_lahir' => 'nullable|string|max:100',
        'tanggal_lahir' => 'nullable|date',
        'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
        'agama' => 'nullable|string|max:50',
        'status_anak' => 'nullable|string|max:50',
        'anak_ke' => 'nullable|string|max:50',
        'alamat' => 'nullable|string',
        'no_hp' => 'nullable|string|max:20',
        'asal_sekolah' => 'nullable|string|max:150',
        'kelas_id' => 'nullable|exists:kelas,id',
        'tanggal_diterima' => 'nullable|date',

        'nama_ayah' => 'nullable|string|max:150',
        'nama_ibu' => 'nullable|string|max:150',
        'alamat_orang_tua' => 'nullable|string',
        'no_hp_orang_tua' => 'nullable|string|max:20',
        'pekerjaan_ayah' => 'nullable|string|max:100',
        'pekerjaan_ibu' => 'nullable|string|max:100',

        'nama_wali' => 'nullable|string|max:150',
        'alamat_wali' => 'nullable|string',
        'no_hp_wali' => 'nullable|string|max:20',
        'pekerjaan_wali' => 'nullable|string|max:100',
    ], [
        'nama.required' => 'Nama siswa wajib diisi.',
        'nis.unique' => 'NIS sudah digunakan.',
        'nisn.unique' => 'NISN sudah digunakan.',
        'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
        'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',
    ]);

    $adaOrangTua = $request->filled('nama_ayah') || $request->filled('nama_ibu');
    $adaWali = $request->filled('nama_wali');

    if (!$adaOrangTua && !$adaWali) {
        return back()
            ->withInput()
            ->withErrors([
                'penanggung_jawab' => 'Data orang tua atau data wali wajib diisi minimal salah satu.',
            ]);
    }

    DB::transaction(function () use ($request, $siswa, $adaOrangTua, $adaWali) {
        $siswa->update([
            'nama' => $request->nama,
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'status_anak' => $request->status_anak,
            'anak_ke' => $request->anak_ke,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'asal_sekolah' => $request->asal_sekolah,
            'kelas_id' => $request->kelas_id,
            'tanggal_diterima' => $request->tanggal_diterima,
        ]);

        if ($adaOrangTua) {
            OrangTua::updateOrCreate(
                ['siswa_id' => $siswa->id],
                [
                    'nama_ayah' => $request->nama_ayah,
                    'nama_ibu' => $request->nama_ibu,
                    'alamat_orang_tua' => $request->alamat_orang_tua,
                    'no_hp_orang_tua' => $request->no_hp_orang_tua,
                    'pekerjaan_ayah' => $request->pekerjaan_ayah,
                    'pekerjaan_ibu' => $request->pekerjaan_ibu,
                ]
            );
        } else {
            OrangTua::where('siswa_id', $siswa->id)->delete();
        }

        if ($adaWali) {
            Wali::updateOrCreate(
                ['siswa_id' => $siswa->id],
                [
                    'nama_wali' => $request->nama_wali,
                    'alamat_wali' => $request->alamat_wali,
                    'no_hp_wali' => $request->no_hp_wali,
                    'pekerjaan_wali' => $request->pekerjaan_wali,
                ]
            );
        } else {
            Wali::where('siswa_id', $siswa->id)->delete();
        }
    });

    return redirect()
        ->route('siswa.index')
        ->with('success', 'Data siswa berhasil diperbarui.');
}

public function destroy($id)
{
    $siswa = Siswa::findOrFail($id);

    DB::transaction(function () use ($siswa) {
        OrangTua::where('siswa_id', $siswa->id)->delete();
        Wali::where('siswa_id', $siswa->id)->delete();
        $siswa->delete();
    });

    return redirect()
        ->route('siswa.index')
        ->with('success', 'Data siswa berhasil dihapus.');
}

public function keluarkanDariKelas($id)
{
    $siswa = Siswa::findOrFail($id);

    $siswa->update([
        'kelas_id' => null,
    ]);

    return back()->with('success', 'Siswa berhasil dikeluarkan dari kelas.');
}
}