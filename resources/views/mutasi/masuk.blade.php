@extends('layouts.app')

@section('title', 'Mutasi Masuk')
@section('page_title', 'Mutasi Masuk')

@section('content')

    {{-- =====================================================
    HEADER
    ===================================================== --}}
    <div class="page-header">

        <div class="page-title">
            Mutasi Masuk
        </div>

        <div class="page-subtitle">
            Tambahkan siswa pindahan dari sekolah lain.
        </div>

    </div>


    {{-- =====================================================
    ACTION
    ===================================================== --}}
    <div class="action-row">

        <a href="{{ route('mutasi.index') }}" class="btn btn-secondary">
            Kembali
        </a>

    </div>


    {{-- =====================================================
    VALIDATION ERROR
    ===================================================== --}}
    @if ($errors->any())

        <div class="alert alert-error">

            <strong>
                Data belum dapat disimpan.
            </strong>

            <ul style="margin: 8px 0 0 18px;">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
    FORM MUTASI MASUK
    ===================================================== --}}
    <div class="form-card">

        <div class="form-section-title">
            Data Siswa Pindahan
        </div>


        <form action="{{ route('mutasi.masuk.store') }}" method="POST" enctype="multipart/form-data"
            data-confirm="Data siswa akan ditambahkan sebagai siswa mutasi masuk dan ditempatkan ke kelas yang dipilih. Yakin ingin menyimpan data mutasi masuk ini?"
            data-confirm-title="Simpan Mutasi Masuk" data-confirm-button="Simpan" data-confirm-type="info">

            @csrf


            {{-- =================================================
            DATA SISWA
            ================================================= --}}
            <div class="form-grid">


                {{-- NAMA --}}
                <div class="form-group">

                    <label class="form-label">
                        Nama Siswa
                        <span style="color:red;">*</span>
                    </label>

                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>

                </div>


                {{-- NIK --}}
                <div class="form-group">

                    <label class="form-label">
                        NIK
                        <span style="color:red;">*</span>
                    </label>

                    <input type="text" name="nik" class="form-control" value="{{ old('nik') }}" required>

                </div>


                {{-- NIS --}}
                <div class="form-group">

                    <label class="form-label">
                        NIS
                    </label>

                    <input type="text" name="nis" class="form-control" value="{{ old('nis') }}">

                </div>


                {{-- NISN --}}
                <div class="form-group">

                    <label class="form-label">
                        NISN
                    </label>

                    <input type="text" name="nisn" class="form-control" value="{{ old('nisn') }}">

                </div>


                {{-- TEMPAT LAHIR --}}
                <div class="form-group">

                    <label class="form-label">
                        Tempat Lahir
                    </label>

                    <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}">

                </div>


                {{-- TANGGAL LAHIR --}}
                <div class="form-group">

                    <label class="form-label">
                        Tanggal Lahir
                    </label>

                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">

                </div>


                {{-- JENIS KELAMIN --}}
                <div class="form-group">

                    <label class="form-label">
                        Jenis Kelamin
                    </label>

                    <select name="jenis_kelamin" class="form-control">

                        <option value="">
                            Pilih Jenis Kelamin
                        </option>

                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki
                        </option>

                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan
                        </option>

                    </select>

                </div>


                {{-- FOTO SISWA --}}
                <div class="form-group">

                    <label class="form-label">
                        Foto Siswa
                    </label>

                    <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.webp">

                    <small style="
                                display:block;
                                margin-top:6px;
                                color:#64748b;
                            ">
                        Maksimal 2 MB.
                    </small>

                </div>

            </div>


            {{-- =================================================
            DATA MUTASI
            ================================================= --}}
            <div class="form-section-title" style="margin-top: 28px;">
                Data Mutasi
            </div>


            <div class="form-grid">


                {{-- SEKOLAH ASAL --}}
                <div class="form-group">

                    <label class="form-label">
                        Sekolah Asal
                        <span style="color:red;">*</span>
                    </label>

                    <input type="text" name="sekolah_asal_tujuan" class="form-control"
                        value="{{ old('sekolah_asal_tujuan') }}" placeholder="Contoh: SMA Negeri 1 Palembang" required>

                </div>


                {{-- KELAS TUJUAN --}}
                <div class="form-group">

                    <label class="form-label">
                        Kelas Tujuan
                        <span style="color:red;">*</span>
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


                {{-- TANGGAL MUTASI --}}
                <div class="form-group">

                    <label class="form-label">
                        Tanggal Mutasi Masuk
                        <span style="color:red;">*</span>
                    </label>

                    <input type="date" name="tanggal_mutasi" class="form-control" value="{{ old('tanggal_mutasi') }}"
                        required>

                </div>


                {{-- KETERANGAN --}}
                <div class="form-group form-full">

                    <label class="form-label">
                        Keterangan
                    </label>

                    <textarea name="keterangan" class="form-control"
                        placeholder="Masukkan keterangan mutasi jika diperlukan">{{ old('keterangan') }}</textarea>

                </div>

            </div>


            {{-- =================================================
            SUBMIT
            ================================================= --}}
            <div class="form-footer" style="
                        margin-top:20px;
                        margin-bottom:0;
                    ">

                <button type="submit" class="btn btn-primary">
                    Simpan Mutasi Masuk
                </button>

            </div>

        </form>

    </div>

@endsection