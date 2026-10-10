@extends('layouts.app')

@section('title', 'Detail Kelas')
@section('page_title', 'Detail Kelas')

@section('content')
    <div class="page-header">
        <div class="page-title">Kelas {{ $kelas->nama_kelas }}</div>
        <div class="page-subtitle">
            Wali kelas: {{ $kelas->wali_kelas ?? '-' }}
        </div>
    </div>

    <div class="action-row">
        <a href="{{ route('kelas.index') }}" class="btn btn-secondary">
            Kembali
        </a>

        <a href="{{ route('kelas.edit', $kelas->id) }}" class="btn btn-warning">
            Edit Kelas
        </a>
    </div>

    <div id="bulk-page" class="table-card">
        <div class="table-header table-header-action">
            <span>Daftar Siswa Kelas {{ $kelas->nama_kelas }}</span>

            <div class="kebab-wrapper">
                <button type="button" class="kebab-button" id="kebab-button">
                    ⋮
                </button>

                <div class="kebab-menu" id="kebab-menu">
                    <button type="button" onclick="enableSelectionMode()">
                        Pilih
                    </button>
                </div>
            </div>
        </div>

        <div class="selection-toolbar">
            <div class="selection-info">
                <strong id="selected-count">0 data dipilih</strong>
            </div>

            <div class="selection-actions">

                <button type="button" class="btn btn-secondary" onclick="showNaikKelasForm()">
                    Naik Kelas
                </button>

                <button type="button" class="btn btn-secondary" onclick="submitAlumni()">
                    Alumni
                </button>

                <form action="{{ route('siswa.bulk-action') }}" method="POST" id="keluarkan-form" class="selection-actions">
                    @csrf

                    <input type="hidden" name="action" value="keluarkan_kelas">

                    <div id="bulk-selected-inputs"></div>

                    <button type="button" class="btn btn-secondary" onclick="return submitKeluarkan()">
                        Keluarkan dari Kelas
                    </button>
                </form>

                <button type="button" class="btn btn-secondary" onclick="disableSelectionMode()">
                    Batal
                </button>

            </div>
        </div>

        <form action="{{ route('kelas.jadikan-alumni', $kelas->id) }}" method="POST" id="alumni-form"
            style="display: none;">
            @csrf

            <div id="alumni-selected-inputs"></div>
        </form>

        <div id="naik-kelas-panel" style="
                                            display: none;
                                            padding: 18px;
                                            background: #f8fafc;
                                            border-bottom: 1px solid #e2e8f0;
                                        ">
            <form action="{{ route('kelas.naik-kelas', $kelas->id) }}" method="POST" id="naik-kelas-form">
                @csrf

                <div id="naik-kelas-selected-inputs"></div>

                <div class="form-grid">

                    <div class="form-group">
                        <label class="form-label">
                            Tahun Ajaran
                        </label>

                        <input type="text" class="form-control"
                            value="{{ $tahunAjaranAktif?->nama_tahun_ajaran ?? 'Belum ada tahun ajaran aktif' }}" disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Kelas Tujuan
                        </label>

                        <select name="kelas_tujuan_id" class="form-control" required>
                            <option value="">
                                Pilih Kelas Tujuan
                            </option>

                            @foreach ($semuaKelas as $kelasItem)

                                @if ($kelasItem->id != $kelas->id)

                                    <option value="{{ $kelasItem->id }}">
                                        {{ $kelasItem->nama_kelas }}
                                    </option>

                                @endif

                            @endforeach

                        </select>
                    </div>

                </div>

                <div style="display: flex; gap: 8px; margin-top: 16px;">

                    <button type="button" class="btn btn-primary" onclick="return submitNaikKelas()">
                        Proses Naik Kelas
                    </button>

                    <button type="button" class="btn btn-secondary" onclick="hideNaikKelasForm()">
                        Batal
                    </button>

                </div>

            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th class="checkbox-column select-cell">
                            <input type="checkbox" id="select-all-siswa" class="table-checkbox">
                        </th>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>Jenis Kelamin</th>
                        <th>No HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($kelas->siswa->sortBy('nama') as $item)
                        <tr>
                            <td class="checkbox-column select-cell">
                                <input type="checkbox" class="table-checkbox siswa-checkbox" value="{{ $item->id }}">
                            </td>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $item->nama }}</td>

                            <td>{{ $item->nis ?? '-' }}</td>

                            <td>{{ $item->nisn ?? '-' }}</td>

                            <td>{{ $item->jenis_kelamin ?? '-' }}</td>

                            <td>{{ $item->no_hp ?? '-' }}</td>

                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('siswa.show', $item->id) }}" class="btn btn-sm btn-info">
                                        Detail
                                    </a>

                                    <a href="{{ route('siswa.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('siswa.keluarkan-kelas', $item->id) }}" method="POST"
                                        class="inline-form"
                                        data-confirm="Siswa akan dikeluarkan dari kelas ini. Yakin ingin melanjutkan?"
                                        data-confirm-title="Keluarkan Siswa" data-confirm-button="Keluarkan"
                                        data-confirm-type="warning">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Keluarkan
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-data">
                                Belum ada siswa di kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const bulkPage = document.getElementById('bulk-page');

        const kebabButton = document.getElementById('kebab-button');
        const kebabMenu = document.getElementById('kebab-menu');

        const selectAllSiswa = document.getElementById('select-all-siswa');
        const siswaCheckboxes = document.querySelectorAll('.siswa-checkbox');
        const selectedCountText = document.getElementById('selected-count');

        const naikKelasPanel = document.getElementById('naik-kelas-panel');
        const naikKelasForm = document.getElementById('naik-kelas-form');
        const naikKelasSelectedInputs = document.getElementById(
            'naik-kelas-selected-inputs'
        );

        const alumniForm = document.getElementById('alumni-form');
        const alumniSelectedInputs = document.getElementById(
            'alumni-selected-inputs'
        );

        const keluarkanForm = document.getElementById('keluarkan-form');
        const bulkSelectedInputs = document.getElementById(
            'bulk-selected-inputs'
        );


        /*
        |--------------------------------------------------------------------------
        | KEBAB MENU
        |--------------------------------------------------------------------------
        */

        if (kebabButton && kebabMenu) {
            kebabButton.addEventListener('click', function (event) {
                event.stopPropagation();

                kebabMenu.classList.toggle('show');
            });


            document.addEventListener('click', function () {
                kebabMenu.classList.remove('show');
            });


            kebabMenu.addEventListener('click', function (event) {
                event.stopPropagation();
            });
        }


        /*
        |--------------------------------------------------------------------------
        | MODE PILIH
        |--------------------------------------------------------------------------
        */

        function enableSelectionMode() {
            bulkPage.classList.add('selection-mode');

            if (kebabMenu) {
                kebabMenu.classList.remove('show');
            }

            updateSelectedCount();
        }


        function disableSelectionMode() {
            bulkPage.classList.remove('selection-mode');

            if (selectAllSiswa) {
                selectAllSiswa.checked = false;
            }

            siswaCheckboxes.forEach(function (checkbox) {
                checkbox.checked = false;
            });

            hideNaikKelasForm();

            updateSelectedCount();
        }


        /*
        |--------------------------------------------------------------------------
        | DATA SISWA YANG DIPILIH
        |--------------------------------------------------------------------------
        */

        function getSelectedSiswaIds() {
            return Array.from(siswaCheckboxes)
                .filter(function (checkbox) {
                    return checkbox.checked;
                })
                .map(function (checkbox) {
                    return checkbox.value;
                });
        }


        function updateSelectedCount() {
            const selectedIds = getSelectedSiswaIds();

            if (selectedCountText) {
                selectedCountText.textContent =
                    selectedIds.length + ' data dipilih';
            }

            if (selectAllSiswa) {
                selectAllSiswa.checked =
                    selectedIds.length === siswaCheckboxes.length &&
                    siswaCheckboxes.length > 0;

                selectAllSiswa.indeterminate =
                    selectedIds.length > 0 &&
                    selectedIds.length < siswaCheckboxes.length;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | PILIH SEMUA
        |--------------------------------------------------------------------------
        */

        if (selectAllSiswa) {
            selectAllSiswa.addEventListener('change', function () {

                siswaCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAllSiswa.checked;
                });

                updateSelectedCount();
            });
        }


        siswaCheckboxes.forEach(function (checkbox) {

            checkbox.addEventListener(
                'change',
                updateSelectedCount
            );

        });


        /*
        |--------------------------------------------------------------------------
        | FORM NAIK KELAS
        |--------------------------------------------------------------------------
        */

        function showNaikKelasForm() {
            const selectedIds = getSelectedSiswaIds();

            if (selectedIds.length === 0) {

                showToast(
                    'Pilih minimal satu siswa terlebih dahulu.',
                    'warning'
                );

                return;
            }

            naikKelasPanel.style.display = 'block';

            naikKelasPanel.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }


        function hideNaikKelasForm() {
            if (naikKelasPanel) {
                naikKelasPanel.style.display = 'none';
            }
        }


        async function submitNaikKelas() {
            const selectedIds = getSelectedSiswaIds();

            if (selectedIds.length === 0) {

                showToast(
                    'Pilih minimal satu siswa terlebih dahulu.',
                    'warning'
                );

                return;
            }


            const kelasTujuan =
                naikKelasForm.querySelector(
                    '[name="kelas_tujuan_id"]'
                );


            if (!kelasTujuan || !kelasTujuan.value) {

                showToast(
                    'Pilih kelas tujuan terlebih dahulu.',
                    'warning'
                );

                if (kelasTujuan) {
                    kelasTujuan.focus();
                }

                return;
            }


            /*
             * Masukkan ID siswa yang dipilih
             * ke dalam form.
             */

            naikKelasSelectedInputs.innerHTML = '';

            selectedIds.forEach(function (id) {

                const input =
                    document.createElement('input');

                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;

                naikKelasSelectedInputs.appendChild(
                    input
                );
            });


            const namaKelasTujuan =
                kelasTujuan.options[
                    kelasTujuan.selectedIndex
                ].text.trim();


            const confirmed = await showConfirm({

                title: 'Konfirmasi Naik Kelas',

                message:
                    selectedIds.length +
                    ' siswa akan dinaikkan ke kelas ' +
                    namaKelasTujuan +
                    '.Yakin ingin melanjutkan?',

                confirmText: 'Naikkan Kelas',

                cancelText: 'Batal',

                type: 'info'
            });


            if (!confirmed) {
                return;
            }


            naikKelasForm.submit();
        }


        /*
        |--------------------------------------------------------------------------
        | JADIKAN ALUMNI
        |--------------------------------------------------------------------------
        */

        async function submitAlumni() {
            const selectedIds = getSelectedSiswaIds();


            if (selectedIds.length === 0) {

                showToast(
                    'Pilih minimal satu siswa terlebih dahulu.',
                    'warning'
                );

                return;
            }


            const confirmed = await showConfirm({

                title: 'Jadikan Alumni',

                message:
                    selectedIds.length +
                    ' siswa akan dijadikan alumni dan dikeluarkan dari kelas aktif. Yakin ingin melanjutkan?',

                confirmText: 'Jadikan Alumni',

                cancelText: 'Batal',

                type: 'warning'
            });


            if (!confirmed) {
                return;
            }


            alumniSelectedInputs.innerHTML = '';


            selectedIds.forEach(function (id) {

                const input =
                    document.createElement('input');

                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;

                alumniSelectedInputs.appendChild(
                    input
                );
            });


            alumniForm.submit();
        }


        /*
        |--------------------------------------------------------------------------
        | KELUARKAN BANYAK SISWA DARI KELAS
        |--------------------------------------------------------------------------
        */

        async function submitKeluarkan() {
            const selectedIds = getSelectedSiswaIds();


            if (selectedIds.length === 0) {

                showToast(
                    'Pilih minimal satu siswa terlebih dahulu.',
                    'warning'
                );

                return;
            }


            const confirmed = await showConfirm({

                title: 'Keluarkan dari Kelas',

                message:
                    selectedIds.length +
                    ' siswa akan dikeluarkan dari kelas ini. Yakin ingin melanjutkan?',

                confirmText: 'Keluarkan',

                cancelText: 'Batal',

                type: 'warning'
            });


            if (!confirmed) {
                return;
            }


            bulkSelectedInputs.innerHTML = '';


            selectedIds.forEach(function (id) {

                const input =
                    document.createElement('input');

                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;

                bulkSelectedInputs.appendChild(
                    input
                );
            });


            keluarkanForm.submit();
        }
    </script>
@endsection