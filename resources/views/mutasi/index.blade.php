@extends('layouts.app')

@section('title', 'Mutasi Siswa')
@section('page_title', 'Mutasi Siswa')

@section('content')
    <div class="page-header">
        <div class="page-title">Mutasi Siswa</div>
        <div class="page-subtitle">
            Kelola dan lihat riwayat mutasi masuk dan mutasi keluar siswa.
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="page-actions">
        <a href="{{ route('mutasi.masuk') }}" class="btn btn-primary">
            Mutasi Masuk
        </a>

        <a href="{{ route('mutasi.keluar') }}" class="btn btn-secondary">
            Mutasi Keluar
        </a>
    </div>

    {{-- FILTER --}}
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

    {{-- TABEL RIWAYAT --}}
    <div id="bulk-page" class="table-card">

        <div class="table-header table-header-action">
            <span>Riwayat Mutasi</span>

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

        {{-- TOOLBAR SAAT MODE PILIH --}}
        <div class="selection-toolbar">
            <div class="selection-info">
                <strong id="selected-count">
                    0 data dipilih
                </strong>
            </div>

            <form action="{{ route('mutasi.bulk-delete') }}" method="POST" class="selection-actions">
                @csrf

                <div id="bulk-selected-inputs" class="bulk-hidden-inputs"></div>

                <button type="submit" class="btn btn-danger" onclick="return submitBulkDelete()">
                    Hapus Riwayat Terpilih
                </button>

                <button type="button" class="btn btn-secondary" onclick="disableSelectionMode()">
                    Batal
                </button>
            </form>
        </div>

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
                                    <td class="checkbox-column select-cell">
                                        <input type="checkbox" class="table-checkbox mutasi-checkbox" value="{{ $item->id }}">
                                    </td>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        {{ $item->tanggal_mutasi
                        ? $item->tanggal_mutasi->format('d-m-Y')
                        : '-' }}
                                    </td>

                                    <td>
                                        {{ $item->siswa->nama ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $item->siswa->nik ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="badge">
                                            {{ $item->jenis_mutasi }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $item->kelas->nama_kelas ?? '-' }}
                                    </td>

                                    <td>
                                        @if ($item->jenis_mutasi === 'Masuk')
                                            <strong>Asal:</strong>
                                        @else
                                            <strong>Tujuan:</strong>
                                        @endif

                                        {{ $item->sekolah_asal_tujuan ?? '-' }}
                                    </td>

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

    <script>
        const bulkPage = document.getElementById('bulk-page');
        const kebabButton = document.getElementById('kebab-button');
        const kebabMenu = document.getElementById('kebab-menu');

        const selectAllMutasi = document.getElementById('select-all-mutasi');
        const mutasiCheckboxes = document.querySelectorAll('.mutasi-checkbox');

        const selectedCountText = document.getElementById('selected-count');
        const bulkSelectedInputs = document.getElementById('bulk-selected-inputs');

        kebabButton.addEventListener('click', function (event) {
            event.stopPropagation();

            kebabMenu.classList.toggle('show');
        });

        document.addEventListener('click', function () {
            kebabMenu.classList.remove('show');
        });

        function enableSelectionMode() {
            bulkPage.classList.add('selection-mode');

            kebabMenu.classList.remove('show');

            updateSelectedCount();
        }

        function disableSelectionMode() {
            bulkPage.classList.remove('selection-mode');

            if (selectAllMutasi) {
                selectAllMutasi.checked = false;
            }

            mutasiCheckboxes.forEach(function (checkbox) {
                checkbox.checked = false;
            });

            updateSelectedCount();
        }

        function getSelectedMutasiIds() {
            return Array.from(mutasiCheckboxes)
                .filter(function (checkbox) {
                    return checkbox.checked;
                })
                .map(function (checkbox) {
                    return checkbox.value;
                });
        }

        function updateSelectedCount() {
            const selectedIds = getSelectedMutasiIds();

            selectedCountText.textContent =
                selectedIds.length + ' data dipilih';

            if (selectAllMutasi) {
                selectAllMutasi.checked =
                    selectedIds.length === mutasiCheckboxes.length &&
                    mutasiCheckboxes.length > 0;
            }
        }

        if (selectAllMutasi) {
            selectAllMutasi.addEventListener('change', function () {
                mutasiCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAllMutasi.checked;
                });

                updateSelectedCount();
            });
        }

        mutasiCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        function submitBulkDelete() {
            const selectedIds = getSelectedMutasiIds();

            if (selectedIds.length === 0) {
                alert('Pilih minimal satu riwayat mutasi terlebih dahulu.');

                return false;
            }

            const konfirmasi = confirm(
                'Yakin ingin menghapus ' +
                selectedIds.length +
                ' riwayat mutasi?'
            );

            if (!konfirmasi) {
                return false;
            }

            bulkSelectedInputs.innerHTML = '';

            selectedIds.forEach(function (id) {
                const input = document.createElement('input');

                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;

                bulkSelectedInputs.appendChild(input);
            });

            return true;
        }
    </script>
@endsection