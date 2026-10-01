@extends('layouts.app')

@section('title', 'Mutasi Masuk')
@section('page_title', 'Mutasi Masuk')

@section('content')
    <div class="page-header">
        <div class="page-title">Mutasi Masuk</div>

        <div class="page-subtitle">
            Tambahkan siswa pindahan dari sekolah lain.
        </div>
    </div>

    <div class="action-row">
        <a href="{{ route('mutasi.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <strong>Data belum dapat disimpan.</strong>

            <ul style="margin: 8px 0 0 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <div class="form-section-title">
            Data Siswa Pindahan
        </div>

        <form action="{{ route('mutasi.masuk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">
                        Nama Siswa <span style="color:red;">*</span>
                    </label>

                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        NIK <span style="color:red;">*</span>
                    </label>

                    <input type="text" name="nik" class="form-control" value="{{ old('nik') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        NIS
                    </label>

                    <input type="text" name="nis" class="form-control" value="{{ old('nis') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        NISN
                    </label>

                    <input type="text" name="nisn" class="form-control" value="{{ old('nisn') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Tempat Lahir
                    </label>

                    <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Tanggal Lahir
                    </label>

                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Jenis Kelamin
                    </label>

                    <select name="jenis_kelamin" class="form-control">
                        <option value="">Pilih Jenis Kelamin</option>

                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki
                        </option>

                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Foto Siswa
                    </label>

                    <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.webp">

                    <small style="display:block; margin-top:6px; color:#64748b;">
                        Maksimal 2 MB.
                    </small>
                </div>

            </div>

            <div class="form-section-title" style="margin-top: 28px;">
                Data Mutasi
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">
                        Sekolah Asal <span style="color:red;">*</span>
                    </label>

                    <input type="text" name="sekolah_asal_tujuan" class="form-control"
                        value="{{ old('sekolah_asal_tujuan') }}" placeholder="Contoh: SMA Negeri 1 Palembang" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Kelas Tujuan <span style="color:red;">*</span>
                    </label>

                    <select name="kelas_id" class="form-control" required>
                        <option value="">
                            Pilih Kelas
                        </option>

                        @foreach ($kelas as $item)
                            <option value="{{ $item->id }}" {{ old('kelas_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Tanggal Mutasi Masuk <span style="color:red;">*</span>
                    </label>

                    <input type="date" name="tanggal_mutasi" class="form-control" value="{{ old('tanggal_mutasi') }}"
                        required>
                </div>

                <div class="form-group form-full">
                    <label class="form-label">
                        Keterangan
                    </label>

                    <textarea name="keterangan" class="form-control"
                        placeholder="Masukkan keterangan mutasi jika diperlukan">{{ old('keterangan') }}</textarea>
                </div>

            </div>

            <div class="form-footer" style="margin-top:20px; margin-bottom:0;">
                <button type="submit" class="btn btn-primary"
                    onclick="return confirm('Yakin ingin menambahkan siswa sebagai mutasi masuk?')">
                    Simpan Mutasi Masuk
                </button>
            </div>

        </form>
    </div>
@endsection