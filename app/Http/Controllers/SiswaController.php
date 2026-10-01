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
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();

        $siswa = Siswa::with('kelas')
            ->where('status_siswa', 'Aktif')

            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->search . '%')
                        ->orWhere('nik', 'like', '%' . $request->search . '%')
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

        try {
            Excel::import(new SiswaImport, $request->file('file'));

            return redirect()
                ->route('siswa.index')
                ->with('success', 'Data siswa berhasil diimport dari Excel.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];

            foreach ($failures as $failure) {
                $errors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return back()
                ->withInput()
                ->withErrors(['file' => $errors]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();

        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|min:3|max:150',
            'nik' => 'required|string|unique:siswa,nik',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nis' => 'nullable|string|min:4|max:50|regex:/^[0-9.]+$/|unique:siswa,nis',
            'nisn' => 'nullable|string|min:6|max:20|regex:/^[0-9]+$/|unique:siswa,nisn',

            'tempat_lahir' => 'nullable|string|min:3|max:100',
            'tanggal_lahir' => 'nullable|date|before:today',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|string|max:50',
            'status_anak' => 'nullable|string|max:50',
            'anak_ke' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|min:10',
            'no_hp' => 'nullable|string|min:8|max:15|regex:/^[0-9]+$/',
            'asal_sekolah' => 'nullable|string|min:3|max:150',
            'kelas_id' => 'nullable|exists:kelas,id',
            'tanggal_diterima' => 'nullable|date|before_or_equal:today',

            'nama_ayah' => 'nullable|string|min:3|max:150',
            'nama_ibu' => 'nullable|string|min:3|max:150',
            'alamat_orang_tua' => 'nullable|string|min:10',
            'no_hp_orang_tua' => 'nullable|string|min:8|max:15|regex:/^[0-9]+$/',
            'pekerjaan_ayah' => 'nullable|string|min:3|max:100',
            'pekerjaan_ibu' => 'nullable|string|min:3|max:100',

            'nama_wali' => 'nullable|string|min:3|max:150',
            'alamat_wali' => 'nullable|string|min:10',
            'no_hp_wali' => 'nullable|string|min:8|max:15|regex:/^[0-9]+$/',
            'pekerjaan_wali' => 'nullable|string|min:3|max:100',
        ], [
            'nama.required' => 'Nama siswa wajib diisi.',
            'nama.min' => 'Nama siswa minimal 3 karakter.',
            'nis.min' => 'NIS minimal 4 digit.',
            'nis.regex' => 'NIS hanya boleh berisi angka dan titik (contoh: 2047.26).',
            'nis.unique' => 'NIS sudah digunakan.',
            'nisn.min' => 'NISN harus 6 digit.',
            'nisn.max' => 'NISN harus 20 digit.',
            'nisn.regex' => 'NISN hanya boleh berisi angka.',
            'nisn.unique' => 'NISN sudah digunakan.',
            'tempat_lahir.min' => 'Tempat lahir minimal 3 karakter.',
            'tanggal_lahir.before' => 'Tanggal lahir tidak boleh tanggal hari ini atau masa depan.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'alamat.min' => 'Alamat minimal 10 karakter.',
            'no_hp.min' => 'No HP minimal 8 digit.',
            'no_hp.max' => 'No HP maksimal 15 digit.',
            'no_hp.regex' => 'No HP hanya boleh berisi angka.',
            'asal_sekolah.min' => 'Asal sekolah minimal 3 karakter.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',
            'tanggal_diterima.before_or_equal' => 'Tanggal diterima tidak boleh tanggal masa depan.',
            'nama_ayah.min' => 'Nama ayah minimal 3 karakter.',
            'nama_ibu.min' => 'Nama ibu minimal 3 karakter.',
            'alamat_orang_tua.min' => 'Alamat orang tua minimal 10 karakter.',
            'no_hp_orang_tua.min' => 'No HP orang tua minimal 8 digit.',
            'no_hp_orang_tua.max' => 'No HP orang tua maksimal 15 digit.',
            'no_hp_orang_tua.regex' => 'No HP orang tua hanya boleh berisi angka.',
            'pekerjaan_ayah.min' => 'Pekerjaan ayah minimal 3 karakter.',
            'pekerjaan_ibu.min' => 'Pekerjaan ibu minimal 3 karakter.',
            'nama_wali.min' => 'Nama wali minimal 3 karakter.',
            'alamat_wali.min' => 'Alamat wali minimal 10 karakter.',
            'no_hp_wali.min' => 'No HP wali minimal 8 digit.',
            'no_hp_wali.max' => 'No HP wali maksimal 15 digit.',
            'no_hp_wali.regex' => 'No HP wali hanya boleh berisi angka.',
            'pekerjaan_wali.min' => 'Pekerjaan wali minimal 3 karakter.',
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
        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('siswa', 'public');
        }

        DB::transaction(function () use ($request, $adaOrangTua, $adaWali, $fotoPath) {
            $siswa = Siswa::create([
                'nama' => $request->nama,
                'nik' => $request->nik,
                'foto' => $fotoPath,
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
            'nama' => 'required|string|min:3|max:150',
            'nik' => 'required|string|unique:siswa,nik,' . $siswa->id,
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'nis' => 'nullable|string|min:4|max:50|regex:/^[0-9.]+$/|unique:siswa,nis,' . $siswa->id,
            'nisn' => 'nullable|string|min:6|max:20|regex:/^[0-9]+$/|unique:siswa,nisn,' . $siswa->id,

            'tempat_lahir' => 'nullable|string|min:3|max:100',
            'tanggal_lahir' => 'nullable|date|before:today',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|string|max:50',
            'status_anak' => 'nullable|string|max:50',
            'anak_ke' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|min:10',
            'no_hp' => 'nullable|string|min:8|max:15|regex:/^[0-9]+$/',
            'asal_sekolah' => 'nullable|string|min:3|max:150',
            'kelas_id' => 'nullable|exists:kelas,id',
            'tanggal_diterima' => 'nullable|date|before_or_equal:today',

            'nama_ayah' => 'nullable|string|min:3|max:150',
            'nama_ibu' => 'nullable|string|min:3|max:150',
            'alamat_orang_tua' => 'nullable|string|min:10',
            'no_hp_orang_tua' => 'nullable|string|min:8|max:15|regex:/^[0-9]+$/',
            'pekerjaan_ayah' => 'nullable|string|min:3|max:100',
            'pekerjaan_ibu' => 'nullable|string|min:3|max:100',

            'nama_wali' => 'nullable|string|min:3|max:150',
            'alamat_wali' => 'nullable|string|min:10',
            'no_hp_wali' => 'nullable|string|min:8|max:15|regex:/^[0-9]+$/',
            'pekerjaan_wali' => 'nullable|string|min:3|max:100',
        ], [
            'nama.required' => 'Nama siswa wajib diisi.',
            'nama.min' => 'Nama siswa minimal 3 karakter.',
            'nis.min' => 'NIS minimal 4 digit.',
            'nis.regex' => 'NIS hanya boleh berisi angka dan titik (contoh: 2047.26).',
            'nis.unique' => 'NIS sudah digunakan.',
            'nisn.min' => 'NISN harus 6 digit.',
            'nisn.max' => 'NISN harus 20 digit.',
            'nisn.regex' => 'NISN hanya boleh berisi angka.',
            'nisn.unique' => 'NISN sudah digunakan.',
            'tempat_lahir.min' => 'Tempat lahir minimal 3 karakter.',
            'tanggal_lahir.before' => 'Tanggal lahir tidak boleh tanggal hari ini atau masa depan.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'alamat.min' => 'Alamat minimal 10 karakter.',
            'no_hp.min' => 'No HP minimal 8 digit.',
            'no_hp.max' => 'No HP maksimal 15 digit.',
            'no_hp.regex' => 'No HP hanya boleh berisi angka.',
            'asal_sekolah.min' => 'Asal sekolah minimal 3 karakter.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',
            'tanggal_diterima.before_or_equal' => 'Tanggal diterima tidak boleh tanggal masa depan.',
            'nama_ayah.min' => 'Nama ayah minimal 3 karakter.',
            'nama_ibu.min' => 'Nama ibu minimal 3 karakter.',
            'alamat_orang_tua.min' => 'Alamat orang tua minimal 10 karakter.',
            'no_hp_orang_tua.min' => 'No HP orang tua minimal 8 digit.',
            'no_hp_orang_tua.max' => 'No HP orang tua maksimal 15 digit.',
            'no_hp_orang_tua.regex' => 'No HP orang tua hanya boleh berisi angka.',
            'pekerjaan_ayah.min' => 'Pekerjaan ayah minimal 3 karakter.',
            'pekerjaan_ibu.min' => 'Pekerjaan ibu minimal 3 karakter.',
            'nama_wali.min' => 'Nama wali minimal 3 karakter.',
            'alamat_wali.min' => 'Alamat wali minimal 10 karakter.',
            'no_hp_wali.min' => 'No HP wali minimal 8 digit.',
            'no_hp_wali.max' => 'No HP wali maksimal 15 digit.',
            'no_hp_wali.regex' => 'No HP wali hanya boleh berisi angka.',
            'pekerjaan_wali.min' => 'Pekerjaan wali minimal 3 karakter.',
        ]);

        $fotoPath = $siswa->foto;

        if ($request->hasFile('foto')) {
            $fotoBaru = $request->file('foto')->store('siswa', 'public');

            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }

            $fotoPath = $fotoBaru;
        }

        $adaOrangTua = $request->filled('nama_ayah') || $request->filled('nama_ibu');
        $adaWali = $request->filled('nama_wali');

        if (!$adaOrangTua && !$adaWali) {
            return back()
                ->withInput()
                ->withErrors([
                    'penanggung_jawab' => 'Data orang tua atau data wali wajib diisi minimal salah satu.',
                ]);
        }

        DB::transaction(function () use ($request, $siswa, $adaOrangTua, $adaWali, $fotoPath) {
            $siswa->update([
                'nama' => $request->nama,
                'nik' => $request->nik,
                'foto' => $fotoPath,
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
        try {
            $siswa = Siswa::findOrFail($id);

            DB::transaction(function () use ($siswa) {
                OrangTua::where('siswa_id', $siswa->id)->delete();
                Wali::where('siswa_id', $siswa->id)->delete();
                if ($siswa->foto) {
                    Storage::disk('public')->delete($siswa->foto);
                }
                $siswa->delete();
            });

            return redirect()
                ->route('siswa.index')
                ->with('success', 'Data siswa berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('siswa.index')
                ->with('error', 'Gagal menghapus data siswa: ' . $e->getMessage());
        }
    }

    public function keluarkanDariKelas($id)
    {
        $siswa = Siswa::findOrFail($id);

        $siswa->update([
            'kelas_id' => null,
        ]);

        return back()->with('success', 'Siswa berhasil dikeluarkan dari kelas.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'integer|exists:siswa,id',
            'action' => 'required|in:hapus,keluarkan_kelas',
        ], [
            'selected_ids.required' => 'Pilih minimal satu data siswa.',
            'selected_ids.min' => 'Pilih minimal satu data siswa.',
            'selected_ids.*.exists' => 'Data siswa yang dipilih tidak valid.',
            'action.required' => 'Aksi belum dipilih.',
            'action.in' => 'Aksi tidak valid.',
        ]);

        $selectedIds = $request->selected_ids;
        $jumlahData = count($selectedIds);

        if ($request->action === 'keluarkan_kelas') {
            Siswa::whereIn('id', $selectedIds)->update([
                'kelas_id' => null,
            ]);

            return back()->with('success', $jumlahData . ' data siswa berhasil dikeluarkan dari kelas.');
        }

        if ($request->action === 'hapus') {
            $fotoSiswa = Siswa::whereIn('id', $selectedIds)
                ->pluck('foto')
                ->filter();

            DB::transaction(function () use ($selectedIds) {
                OrangTua::whereIn('siswa_id', $selectedIds)->delete();
                Wali::whereIn('siswa_id', $selectedIds)->delete();
                Siswa::whereIn('id', $selectedIds)->delete();
            });

            foreach ($fotoSiswa as $foto) {
                Storage::disk('public')->delete($foto);
            }

            return back()->with(
                'success',
                $jumlahData . ' data siswa berhasil dihapus.'
            );
        }

        return back()->with('error', 'Aksi tidak valid.');
    }
}
