@extends('layouts.app')

@section('title', 'Mutasi Keluar')
@section('page_title', 'Mutasi Keluar')

@section('content')

    {{-- =====================================================
    HEADER
    ===================================================== --}}
    <div class="page-header">

        <div class="page-title">
            Mutasi Keluar
        </div>

        <div class="page-subtitle">
            Catat siswa yang keluar atau pindah dari sekolah.
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
    FORM MUTASI KELUAR
    ===================================================== --}}
    <div class="form-card">

        <div class="form-section-title">
            Data Mutasi Keluar
        </div>


        <form action="{{ route('mutasi.keluar.store') }}" method="POST" id="mutasi-keluar-form">

            @csrf


            <div class="form-grid">


                {{-- =================================================
                SISWA
                ================================================= --}}
                <div class="form-group">

                    <label class="form-label">
                        Siswa
                        <span style="color: red;">*</span>
                    </label>


                    @php
                        $oldSiswa = $siswa->firstWhere(
                            'id',
                            old('siswa_id')
                        );
                    @endphp


                    <input type="text" id="siswa-search" class="form-control" list="daftar-siswa"
                        placeholder="Ketik nama siswa..." autocomplete="off" value="{{ $oldSiswa
        ? $oldSiswa->nama
        . ' - NIS: '
        . ($oldSiswa->nis ?? '-')
        . ' - '
        . ($oldSiswa->kelas->nama_kelas ?? 'Belum ada kelas')
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


                {{-- =================================================
                TANGGAL MUTASI
                ================================================= --}}
                <div class="form-group">

                    <label class="form-label">
                        Tanggal Mutasi
                        <span style="color: red;">*</span>
                    </label>

                    <input type="date" name="tanggal_mutasi" class="form-control" value="{{ old('tanggal_mutasi') }}"
                        required>

                </div>


                {{-- =================================================
                SEKOLAH TUJUAN
                ================================================= --}}
                <div class="form-group">

                    <label class="form-label">
                        Sekolah Tujuan
                    </label>

                    <input type="text" name="sekolah_asal_tujuan" class="form-control"
                        value="{{ old('sekolah_asal_tujuan') }}" placeholder="Contoh: SMA Xaverius 1 Palembang">

                </div>


                {{-- =================================================
                KETERANGAN
                ================================================= --}}
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
                        margin-top: 20px;
                        margin-bottom: 0;
                    ">

                <button type="submit" class="btn btn-primary" id="submit-mutasi-keluar">
                    Simpan Mutasi Keluar
                </button>

            </div>

        </form>

    </div>



    {{-- =====================================================
    JAVASCRIPT
    ===================================================== --}}
    <script>

        /*
        |--------------------------------------------------------------------------
        | ELEMENT
        |--------------------------------------------------------------------------
        */

        const siswaSearch =
            document.getElementById(
                'siswa-search'
            );

        const siswaId =
            document.getElementById(
                'siswa-id'
            );

        const daftarSiswa =
            document.getElementById(
                'daftar-siswa'
            );

        const mutasiForm =
            document.getElementById(
                'mutasi-keluar-form'
            );



        /*
        |--------------------------------------------------------------------------
        | MENCARI ID SISWA BERDASARKAN PILIHAN DATALIST
        |--------------------------------------------------------------------------
        */

        function setSiswaId() {

            const nilai =
                siswaSearch.value.trim();

            const options =
                daftarSiswa.querySelectorAll(
                    'option'
                );


            /*
             * Kosongkan terlebih dahulu.
             * ID hanya diisi apabila nilai input
             * benar-benar cocok dengan pilihan.
             */
            siswaId.value =
                '';


            options.forEach(
                function (option) {

                    if (
                        option.value === nilai
                    ) {

                        siswaId.value =
                            option.dataset.id;

                    }

                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | SAAT SISWA DIKETIK / DIPILIH
        |--------------------------------------------------------------------------
        */

        siswaSearch.addEventListener(
            'input',
            setSiswaId
        );


        siswaSearch.addEventListener(
            'change',
            setSiswaId
        );



        /*
        |--------------------------------------------------------------------------
        | SUBMIT MUTASI KELUAR
        |--------------------------------------------------------------------------
        */

        mutasiForm.addEventListener(
            'submit',
            async function (event) {

                /*
                 * Hentikan submit normal terlebih dahulu.
                 */
                event.preventDefault();


                /*
                 * Pastikan siswa yang dipilih
                 * berasal dari daftar.
                 */
                setSiswaId();


                if (!siswaId.value) {

                    showToast(
                        'Silakan pilih siswa dari daftar yang muncul.',
                        'warning'
                    );


                    siswaSearch.focus();

                    return;

                }


                /*
                 * Ambil nama siswa untuk ditampilkan
                 * pada modal konfirmasi.
                 */
                const siswaDipilih =
                    siswaSearch.value;


                /*
                 * Modal konfirmasi custom.
                 */
                const confirmed =
                    await showConfirm({

                        title:
                            'Simpan Mutasi Keluar',

                        message:
                            'Siswa yang dipilih akan diproses sebagai mutasi keluar. Pastikan siswa, tanggal mutasi, dan sekolah tujuan sudah benar sebelum melanjutkan.',

                        confirmText:
                            'Proses Mutasi',

                        cancelText:
                            'Batal',

                        type:
                            'warning'

                    });


                /*
                 * Pengguna membatalkan.
                 */
                if (!confirmed) {

                    return;

                }


                /*
                 * Kirim form secara langsung.
                 *
                 * Menggunakan form.submit()
                 * agar event submit tidak berjalan
                 * untuk kedua kalinya.
                 */
                mutasiForm.submit();

            }
        );

    </script>

@endsection