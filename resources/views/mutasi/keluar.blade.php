@extends('layouts.app')

@section('title', 'Mutasi Keluar')
@section('page_title', 'Mutasi Keluar')

@section('content')
    <div class="page-header">
        <div class="page-title">Mutasi Keluar</div>
        <div class="page-subtitle">
            Catat siswa yang keluar atau pindah dari sekolah.
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
            Data Mutasi Keluar
        </div>

        <form action="{{ route('mutasi.keluar.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">
                        Siswa <span style="color: red;">*</span>
                    </label>

                    @php
                        $oldSiswa = $siswa->firstWhere('id', old('siswa_id'));
                    @endphp

                    <input type="text" id="siswa-search" class="form-control" list="daftar-siswa"
                        placeholder="Ketik nama siswa..." autocomplete="off" value="{{ $oldSiswa
        ? $oldSiswa->nama . ' - NIS: ' . ($oldSiswa->nis ?? '-') . ' - ' . ($oldSiswa->kelas->nama_kelas ?? 'Belum ada kelas')
        : ''
                }}" required>

                    <input type="hidden" name="siswa_id" id="siswa-id" value="{{ old('siswa_id') }}">

                    <datalist id="daftar-siswa">
                        @foreach ($siswa as $item)
                            <option
                                value="{{ $item->nama }} - NIS: {{ $item->nis ?? '-' }} - {{ $item->kelas->nama_kelas ?? 'Belum ada kelas' }}"
                                data-id="{{ $item->id }}"></option>
                        @endforeach
                    </datalist>

                    @error('siswa_id')
                        <div class="form-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Tanggal Mutasi <span style="color: red;">*</span>
                    </label>

                    <input type="date" name="tanggal_mutasi" class="form-control" value="{{ old('tanggal_mutasi') }}"
                        required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Sekolah Tujuan
                    </label>

                    <input type="text" name="sekolah_asal_tujuan" class="form-control"
                        value="{{ old('sekolah_asal_tujuan') }}" placeholder="Contoh: SMA Xaverius 1 Palembang">
                </div>

                <div class="form-group form-full">
                    <label class="form-label">
                        Keterangan
                    </label>

                    <textarea name="keterangan" class="form-control"
                        >{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div class="form-footer" style="margin-top: 20px; margin-bottom: 0;">
                <button type="submit" class="btn btn-primary"
                    onclick="return confirm('Yakin ingin memproses siswa ini sebagai mutasi keluar?')">
                    Simpan Mutasi Keluar
                </button>
            </div>
        </form>
    </div>

    <script>
        const siswaSearch = document.getElementById('siswa-search');
        const siswaId = document.getElementById('siswa-id');
        const daftarSiswa = document.getElementById('daftar-siswa');

        function setSiswaId() {
            const nilai = siswaSearch.value;
            const options = daftarSiswa.querySelectorAll('option');

            siswaId.value = '';

            options.forEach(option => {
                if (option.value === nilai) {
                    siswaId.value = option.dataset.id;
                }
            });
        }

        siswaSearch.addEventListener('input', setSiswaId);

        const mutasiForm = siswaSearch.closest('form');

        mutasiForm.addEventListener('submit', function (event) {
            setSiswaId();

            if (!siswaId.value) {
                event.preventDefault();

                alert('Silakan pilih siswa dari daftar yang muncul.');

                siswaSearch.focus();
            }
        });
    </script>
@endsection