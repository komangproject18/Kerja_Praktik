@extends('layouts.app')

@section('title', 'Tambah Kelas')
@section('page_title', 'Tambah Kelas')

@section('content')
    <div class="page-header">
        <div class="page-title">Tambah Kelas</div>
        <div class="page-subtitle">
            Tambahkan data kelas yang akan digunakan saat input data siswa.
        </div>
    </div>

    <div class="form-card">
        <form action="{{ route('kelas.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nama_kelas" class="form-label">Nama Kelas</label>
                <input type="text" name="nama_kelas" id="nama_kelas" class="form-control" value="{{ old('nama_kelas') }}" placeholder="Contoh: X TKJ 1">

                @error('nama_kelas')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="wali_kelas" class="form-label">Wali Kelas</label>
                <input type="text" name="wali_kelas" id="wali_kelas" class="form-control" value="{{ old('wali_kelas') }}" placeholder="Masukkan nama wali kelas">

                @error('wali_kelas')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="action-row">
                <button type="submit" class="btn btn-primary">
                    Simpan Data
                </button>

                <a href="{{ route('kelas.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection