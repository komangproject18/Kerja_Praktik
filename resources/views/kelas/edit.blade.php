@extends('layouts.app')

@section('title', 'Edit Kelas')
@section('page_title', 'Edit Kelas')

@section('content')
    <div class="page-header">
        <div class="page-title">Edit Kelas</div>
        <div class="page-subtitle">
            Perbarui nama kelas dan wali kelas.
        </div>
    </div>

    <div class="form-card">
        <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nama_kelas" class="form-label">Nama Kelas</label>
                <input type="text" name="nama_kelas" id="nama_kelas" class="form-control" value="{{ old('nama_kelas', $kelas->nama_kelas) }}">

                @error('nama_kelas')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="wali_kelas" class="form-label">Wali Kelas</label>
                <input type="text" name="wali_kelas" id="wali_kelas" class="form-control" value="{{ old('wali_kelas', $kelas->wali_kelas) }}">

                @error('wali_kelas')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="action-row">
                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

                <a href="{{ route('kelas.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection