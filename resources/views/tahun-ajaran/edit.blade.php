@extends('layouts.app')

@section('title', 'Edit Tahun Ajaran')
@section('page_title', 'Edit Tahun Ajaran')

@section('content')

    <div class="page-header">

        <div class="page-title">
            Edit Tahun Ajaran
        </div>

        <div class="page-subtitle">
            Perbarui periode tahun ajaran.
        </div>

    </div>


    <div class="action-row">

        <a href="{{ route('tahun-ajaran.index') }}" class="btn btn-secondary">
            Kembali
        </a>

    </div>


    <div class="form-card">

        <div class="form-section-title">
            Data Tahun Ajaran
        </div>

        <form action="{{ route('tahun-ajaran.update', $tahunAjaran->id) }}" method="POST">

            @csrf
            @method('PUT')


            <div class="form-group">

                <label class="form-label">
                    Tahun Ajaran
                </label>

                <input type="text" name="nama_tahun_ajaran" class="form-control"
                    value="{{ old('nama_tahun_ajaran', $tahunAjaran->nama_tahun_ajaran) }}" required>

                @error('nama_tahun_ajaran')

                    <div class="form-error">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <div class="form-footer" style="margin-top: 20px; margin-bottom: 0;">

                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

@endsection