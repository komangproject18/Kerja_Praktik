<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $kelas = Kelas::with('siswa')
            ->findOrFail($id);

        return view('kelas.show', compact('kelas'));
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