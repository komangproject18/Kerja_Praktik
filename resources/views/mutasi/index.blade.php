@extends('layouts.app')

@section('title', 'Mutasi Siswa')
@section('page_title', 'Mutasi Siswa')

@section('content')

    <div class="page-header">

        <div class="page-title">
            Mutasi Siswa
        </div>

        <div class="page-subtitle">
            Kelola dan lihat riwayat mutasi masuk dan mutasi keluar siswa.
        </div>

    </div>


    {{-- =====================================================
    ACTION
    ===================================================== --}}
    <div class="page-actions">

        <a href="{{ route('mutasi.masuk') }}" class="btn btn-primary">
            Mutasi Masuk
        </a>


        <a href="{{ route('mutasi.keluar') }}" class="btn btn-secondary">
            Mutasi Keluar
        </a>

    </div>


    {{-- =====================================================
    FILTER
    ===================================================== --}}
    <div class="form-card" style="margin-bottom: 20px;">

        <form action="{{ route('mutasi.index') }}" method="GET">

            <div style="
                        display: grid;
                        grid-template-columns: 1fr auto;
                        gap: 12px;
                        align-items: end;
                    ">

                <div class="form-group" style="margin-bottom: 0;">

                    <label for="jenis" class="form-label">
                        Filter Jenis Mutasi
                    </label>


                    <select name="jenis" id="jenis" class="form-control" onchange="this.form.submit()">

                        <option value="">
                            Semua Mutasi
                        </option>


                        <option value="Masuk" {{ request('jenis') == 'Masuk' ? 'selected' : '' }}>
                            Mutasi Masuk
                        </option>


                        <option value="Keluar" {{ request('jenis') == 'Keluar' ? 'selected' : '' }}>
                            Mutasi Keluar
                        </option>

                    </select>

                </div>


                <a href="{{ route('mutasi.index') }}" class="btn btn-secondary">
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- =====================================================
    TABEL RIWAYAT MUTASI
    ===================================================== --}}
    <div id="bulk-page" class="table-card">


        {{-- HEADER --}}
        <div class="table-header table-header-action">

            <span>
                Riwayat Mutasi
            </span>


            <div class="kebab-wrapper">

                <button type="button" class="kebab-button" id="kebab-button" aria-label="Menu pilihan">
                    ⋮
                </button>


                <div class="kebab-menu" id="kebab-menu">

                    <button type="button" onclick="enableSelectionMode()">
                        Pilih
                    </button>

                </div>

            </div>

        </div>


        {{-- =================================================
        TOOLBAR MODE PILIH
        ================================================= --}}
        <div class="selection-toolbar">

            <div class="selection-info">

                <strong id="selected-count">
                    0 data dipilih
                </strong>

            </div>


            <form action="{{ route('mutasi.bulk-delete') }}" method="POST" class="selection-actions" id="bulk-delete-form">

                @csrf


                <div id="bulk-selected-inputs" class="bulk-hidden-inputs"></div>


                <button type="button" class="btn btn-danger" onclick="submitBulkDelete()">
                    Hapus Riwayat Terpilih
                </button>


                <button type="button" class="btn btn-secondary" onclick="disableSelectionMode()">
                    Batal
                </button>

            </form>

        </div>


        {{-- =================================================
        TABLE
        ================================================= --}}
        <div class="table-responsive">

            <table>

                <thead>

                    <tr>

                        <th class="checkbox-column select-cell">

                            <input type="checkbox" id="select-all-mutasi" class="table-checkbox">

                        </th>

                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>NIK</th>
                        <th>Jenis</th>
                        <th>Kelas</th>
                        <th>Sekolah Asal / Tujuan</th>
                        <th>Keterangan</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($mutasi as $item)

                                <tr>

                                    {{-- CHECKBOX --}}
                                    <td class="checkbox-column select-cell">

                                        <input type="checkbox" class="table-checkbox mutasi-checkbox" value="{{ $item->id }}">

                                    </td>


                                    {{-- NOMOR --}}
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    {{-- TANGGAL --}}
                                    <td>

                                        {{ $item->tanggal_mutasi
                        ? $item->tanggal_mutasi->format('d-m-Y')
                        : '-' }}

                                    </td>


                                    {{-- NAMA SISWA --}}
                                    <td>
                                        {{ $item->siswa->nama ?? '-' }}
                                    </td>


                                    {{-- NIK --}}
                                    <td>
                                        {{ $item->siswa->nik ?? '-' }}
                                    </td>


                                    {{-- JENIS MUTASI --}}
                                    <td>

                                        <span class="badge">
                                            {{ $item->jenis_mutasi }}
                                        </span>

                                    </td>


                                    {{-- KELAS --}}
                                    <td>
                                        {{ $item->kelas->nama_kelas ?? '-' }}
                                    </td>


                                    {{-- SEKOLAH ASAL / TUJUAN --}}
                                    <td>

                                        @if ($item->jenis_mutasi === 'Masuk')

                                            <strong>
                                                Asal:
                                            </strong>

                                        @else

                                            <strong>
                                                Tujuan:
                                            </strong>

                                        @endif


                                        {{ $item->sekolah_asal_tujuan ?? '-' }}

                                    </td>


                                    {{-- KETERANGAN --}}
                                    <td>
                                        {{ $item->keterangan ?? '-' }}
                                    </td>

                                </tr>


                    @empty

                        <tr>

                            <td colspan="9" class="empty-data">
                                Belum ada riwayat mutasi siswa.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

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

        const bulkPage =
            document.getElementById(
                'bulk-page'
            );

        const kebabButton =
            document.getElementById(
                'kebab-button'
            );

        const kebabMenu =
            document.getElementById(
                'kebab-menu'
            );

        const selectAllMutasi =
            document.getElementById(
                'select-all-mutasi'
            );

        const mutasiCheckboxes =
            document.querySelectorAll(
                '.mutasi-checkbox'
            );

        const selectedCountText =
            document.getElementById(
                'selected-count'
            );

        const bulkSelectedInputs =
            document.getElementById(
                'bulk-selected-inputs'
            );

        const bulkDeleteForm =
            document.getElementById(
                'bulk-delete-form'
            );



        /*
        |--------------------------------------------------------------------------
        | KEBAB MENU
        |--------------------------------------------------------------------------
        */

        if (
            kebabButton &&
            kebabMenu
        ) {

            kebabButton.addEventListener(
                'click',
                function (event) {

                    event.stopPropagation();

                    kebabMenu.classList.toggle(
                        'show'
                    );

                }
            );


            kebabMenu.addEventListener(
                'click',
                function (event) {

                    event.stopPropagation();

                }
            );


            document.addEventListener(
                'click',
                function () {

                    kebabMenu.classList.remove(
                        'show'
                    );

                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | MODE PILIH
        |--------------------------------------------------------------------------
        */

        function enableSelectionMode() {

            bulkPage.classList.add(
                'selection-mode'
            );


            if (kebabMenu) {

                kebabMenu.classList.remove(
                    'show'
                );

            }


            updateSelectedCount();
        }



        function disableSelectionMode() {

            bulkPage.classList.remove(
                'selection-mode'
            );


            if (selectAllMutasi) {

                selectAllMutasi.checked =
                    false;

                selectAllMutasi.indeterminate =
                    false;

            }


            mutasiCheckboxes.forEach(
                function (checkbox) {

                    checkbox.checked =
                        false;

                }
            );


            updateSelectedCount();
        }



        /*
        |--------------------------------------------------------------------------
        | AMBIL ID RIWAYAT YANG DIPILIH
        |--------------------------------------------------------------------------
        */

        function getSelectedMutasiIds() {

            return Array
                .from(mutasiCheckboxes)
                .filter(
                    function (checkbox) {

                        return checkbox.checked;

                    }
                )
                .map(
                    function (checkbox) {

                        return checkbox.value;

                    }
                );

        }



        /*
        |--------------------------------------------------------------------------
        | UPDATE JUMLAH DATA DIPILIH
        |--------------------------------------------------------------------------
        */

        function updateSelectedCount() {

            const selectedIds =
                getSelectedMutasiIds();


            if (selectedCountText) {

                selectedCountText.textContent =
                    selectedIds.length +
                    ' data dipilih';

            }


            if (selectAllMutasi) {

                selectAllMutasi.checked =
                    selectedIds.length ===
                    mutasiCheckboxes.length &&
                    mutasiCheckboxes.length > 0;


                selectAllMutasi.indeterminate =
                    selectedIds.length > 0 &&
                    selectedIds.length <
                    mutasiCheckboxes.length;

            }

        }



        /*
        |--------------------------------------------------------------------------
        | PILIH SEMUA
        |--------------------------------------------------------------------------
        */

        if (selectAllMutasi) {

            selectAllMutasi.addEventListener(
                'change',
                function () {

                    mutasiCheckboxes.forEach(
                        function (checkbox) {

                            checkbox.checked =
                                selectAllMutasi.checked;

                        }
                    );


                    updateSelectedCount();

                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | CHECKBOX SATU PER SATU
        |--------------------------------------------------------------------------
        */

        mutasiCheckboxes.forEach(
            function (checkbox) {

                checkbox.addEventListener(
                    'change',
                    updateSelectedCount
                );

            }
        );



        /*
        |--------------------------------------------------------------------------
        | HAPUS RIWAYAT MUTASI TERPILIH
        |--------------------------------------------------------------------------
        */

        async function submitBulkDelete() {

            const selectedIds =
                getSelectedMutasiIds();


            /*
             * Tidak ada data yang dipilih.
             */
            if (selectedIds.length === 0) {

                showToast(
                    'Pilih minimal satu riwayat mutasi terlebih dahulu.',
                    'warning'
                );

                return;

            }


            /*
             * Tampilkan modal konfirmasi.
             */
            const confirmed =
                await showConfirm({

                    title:
                        'Hapus Riwayat Mutasi',

                    message:
                        selectedIds.length +
                        ' riwayat mutasi akan dihapus. Tindakan ini tidak dapat dibatalkan. Yakin ingin melanjutkan?',

                    confirmText:
                        'Hapus ' +
                        selectedIds.length +
                        ' Riwayat',

                    cancelText:
                        'Batal',

                    type:
                        'danger'

                });


            if (!confirmed) {

                return;

            }


            /*
             * Bersihkan input sebelumnya.
             */
            bulkSelectedInputs.innerHTML =
                '';


            /*
             * Masukkan ID riwayat mutasi
             * yang dipilih.
             */
            selectedIds.forEach(
                function (id) {

                    const input =
                        document.createElement(
                            'input'
                        );


                    input.type =
                        'hidden';

                    input.name =
                        'selected_ids[]';

                    input.value =
                        id;


                    bulkSelectedInputs
                        .appendChild(
                            input
                        );

                }
            );


            /*
             * Kirim form setelah
             * konfirmasi diterima.
             */
            bulkDeleteForm.submit();

        }

    </script>

@endsection