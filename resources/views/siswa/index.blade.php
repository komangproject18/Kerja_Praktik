@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page_title', 'Data Siswa')

@section('content')

    <div class="page-header">
        <div class="page-title">Data Siswa</div>

        <div class="page-subtitle">
            Kelola data siswa, kelas penerimaan, dan informasi siswa.
        </div>
    </div>


    {{-- =====================================================
    ACTION
    ===================================================== --}}
    <div class="page-actions">
        <a href="{{ url('/siswa/create') }}" class="btn btn-primary">
            Tambah Siswa
        </a>
    </div>


    {{-- =====================================================
    FILTER DAN PENCARIAN
    ===================================================== --}}
    <div class="form-card" style="margin-bottom: 20px;">

        <form action="{{ route('siswa.index') }}" method="GET">

            <div style="
                    display: grid;
                    grid-template-columns: 2fr 1fr auto;
                    gap: 12px;
                    align-items: end;
                ">

                <div class="form-group" style="margin-bottom: 0;">

                    <label for="search" class="form-label">
                        Cari Siswa
                    </label>

                    <input type="text" name="search" id="search" class="form-control"
                        placeholder="Cari nama, NIK, NIS, atau NISN" value="{{ request('search') }}">

                </div>


                <div class="form-group" style="margin-bottom: 0;">

                    <label for="kelas_id" class="form-label">
                        Filter Kelas
                    </label>

                    <select name="kelas_id" id="kelas_id" class="form-control">

                        <option value="">
                            Semua Kelas
                        </option>

                        @foreach ($kelas as $item)

                            <option value="{{ $item->id }}" {{ request('kelas_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_kelas }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div style="display: flex; gap: 8px;">

                    <button type="submit" class="btn btn-primary">
                        Cari
                    </button>

                    <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>


    {{-- =====================================================
    TABEL DATA SISWA
    ===================================================== --}}
    <div id="bulk-page" class="table-card">


        {{-- HEADER TABEL --}}
        <div class="table-header table-header-action">

            <span>
                Daftar Siswa
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


            <form action="{{ route('siswa.bulk-action') }}" method="POST" class="selection-actions" id="bulk-action-form">

                @csrf


                <input type="hidden" name="action" id="bulk-action-type">


                <div id="bulk-selected-inputs" class="bulk-hidden-inputs"></div>


                <button type="button" class="btn btn-danger" onclick="submitBulkAction('hapus')">
                    Hapus
                </button>


                <button type="button" class="btn btn-secondary" onclick="disableSelectionMode()">
                    Batal
                </button>

            </form>

        </div>


        {{-- =================================================
        TABEL
        ================================================= --}}
        <div class="table-responsive">

            <table>

                <thead>

                    <tr>

                        <th class="checkbox-column select-cell">

                            <input type="checkbox" id="select-all-siswa" class="table-checkbox">

                        </th>

                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>Jenis Kelamin</th>
                        <th>Kelas</th>
                        <th>Asal Sekolah</th>
                        <th>Tanggal Diterima</th>
                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($siswa as $item)

                                <tr>

                                    {{-- CHECKBOX --}}
                                    <td class="checkbox-column select-cell">

                                        <input type="checkbox" class="table-checkbox siswa-checkbox" value="{{ $item->id }}">

                                    </td>


                                    {{-- NOMOR --}}
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    {{-- FOTO --}}
                                    <td>

                                        @if ($item->foto)

                                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" style="
                                                                width: 42px;
                                                                height: 52px;
                                                                object-fit: cover;
                                                                border-radius: 7px;
                                                                border: 1px solid #e2e8f0;
                                                            ">

                                        @else

                                            <div style="
                                                                width: 42px;
                                                                height: 52px;
                                                                background: #f1f5f9;
                                                                border-radius: 7px;
                                                                display: flex;
                                                                align-items: center;
                                                                justify-content: center;
                                                                color: #94a3b8;
                                                                font-size: 11px;
                                                                text-align: center;
                                                            ">
                                                Tidak ada
                                            </div>

                                        @endif

                                    </td>


                                    {{-- NAMA --}}
                                    <td>
                                        {{ $item->nama }}
                                    </td>


                                    {{-- NIK --}}
                                    <td>
                                        {{ $item->nik ?? '-' }}
                                    </td>


                                    {{-- NIS --}}
                                    <td>
                                        {{ $item->nis ?? '-' }}
                                    </td>


                                    {{-- NISN --}}
                                    <td>
                                        {{ $item->nisn ?? '-' }}
                                    </td>


                                    {{-- JENIS KELAMIN --}}
                                    <td>
                                        {{ $item->jenis_kelamin ?? '-' }}
                                    </td>


                                    {{-- KELAS --}}
                                    <td>

                                        @if ($item->kelas)

                                            <span class="badge">
                                                {{ $item->kelas->nama_kelas }}
                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ASAL SEKOLAH --}}
                                    <td>
                                        {{ $item->asal_sekolah ?? '-' }}
                                    </td>


                                    {{-- TANGGAL DITERIMA --}}
                                    <td>

                                        {{ $item->tanggal_diterima
                        ? date('d-m-Y', strtotime($item->tanggal_diterima))
                        : '-' }}

                                    </td>


                                    {{-- AKSI --}}
                                    <td>

                                        <div class="action-buttons">


                                            {{-- DETAIL --}}
                                            <a href="{{ route('siswa.show', $item->id) }}" class="btn btn-sm btn-info">
                                                Detail
                                            </a>


                                            {{-- EDIT --}}
                                            <a href="{{ route('siswa.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                                Edit
                                            </a>


                                            {{-- HAPUS --}}
                                            <form action="{{ route('siswa.destroy', $item->id) }}" method="POST" class="inline-form"
                                                data-confirm="Data siswa {{ $item->nama }} akan dihapus secara permanen beserta data orang tua dan wali yang terkait. Yakin ingin menghapus data ini?"
                                                data-confirm-title="Hapus Data Siswa" data-confirm-button="Hapus"
                                                data-confirm-type="danger">

                                                @csrf
                                                @method('DELETE')


                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                    @empty

                        <tr>

                            <td colspan="12" class="empty-data">
                                Belum ada data siswa.
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

        const selectAllSiswa =
            document.getElementById(
                'select-all-siswa'
            );

        const siswaCheckboxes =
            document.querySelectorAll(
                '.siswa-checkbox'
            );

        const selectedCountText =
            document.getElementById(
                'selected-count'
            );

        const bulkActionType =
            document.getElementById(
                'bulk-action-type'
            );

        const bulkSelectedInputs =
            document.getElementById(
                'bulk-selected-inputs'
            );

        const bulkActionForm =
            document.getElementById(
                'bulk-action-form'
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


            if (selectAllSiswa) {

                selectAllSiswa.checked = false;

                selectAllSiswa.indeterminate = false;

            }


            siswaCheckboxes.forEach(
                function (checkbox) {

                    checkbox.checked = false;

                }
            );


            updateSelectedCount();
        }



        /*
        |--------------------------------------------------------------------------
        | AMBIL ID SISWA YANG DIPILIH
        |--------------------------------------------------------------------------
        */

        function getSelectedSiswaIds() {

            return Array
                .from(siswaCheckboxes)
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
                getSelectedSiswaIds();


            if (selectedCountText) {

                selectedCountText.textContent =
                    selectedIds.length +
                    ' data dipilih';

            }


            if (selectAllSiswa) {

                selectAllSiswa.checked =
                    selectedIds.length ===
                    siswaCheckboxes.length &&
                    siswaCheckboxes.length > 0;


                selectAllSiswa.indeterminate =
                    selectedIds.length > 0 &&
                    selectedIds.length <
                    siswaCheckboxes.length;

            }

        }



        /*
        |--------------------------------------------------------------------------
        | PILIH SEMUA
        |--------------------------------------------------------------------------
        */

        if (selectAllSiswa) {

            selectAllSiswa.addEventListener(
                'change',
                function () {

                    siswaCheckboxes.forEach(
                        function (checkbox) {

                            checkbox.checked =
                                selectAllSiswa.checked;

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

        siswaCheckboxes.forEach(
            function (checkbox) {

                checkbox.addEventListener(
                    'change',
                    updateSelectedCount
                );

            }
        );



        /*
        |--------------------------------------------------------------------------
        | BULK ACTION
        |--------------------------------------------------------------------------
        */

        async function submitBulkAction(action) {

            const selectedIds =
                getSelectedSiswaIds();


            /*
             * Tidak ada data dipilih
             */
            if (selectedIds.length === 0) {

                showToast(
                    'Pilih minimal satu data siswa terlebih dahulu.',
                    'warning'
                );

                return;
            }


            /*
             * Saat ini bulk action yang digunakan
             * pada halaman ini adalah hapus.
             */
            if (action === 'hapus') {

                const confirmed =
                    await showConfirm({

                        title:
                            'Hapus Data Siswa',

                        message:
                            selectedIds.length +
                            ' data siswa akan dihapus secara permanen beserta data yang terkait. Yakin ingin melanjutkan?',

                        confirmText:
                            'Hapus ' +
                            selectedIds.length +
                            ' Data',

                        cancelText:
                            'Batal',

                        type:
                            'danger'

                    });


                if (!confirmed) {

                    return;

                }

            }


            /*
             * Masukkan jenis action.
             */
            bulkActionType.value =
                action;


            /*
             * Bersihkan input lama.
             */
            bulkSelectedInputs.innerHTML =
                '';


            /*
             * Masukkan setiap ID siswa
             * ke form sebagai:
             *
             * selected_ids[]
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
             * Kirim form setelah dikonfirmasi.
             */
            bulkActionForm.submit();
        }

    </script>

@endsection