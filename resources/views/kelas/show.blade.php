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

    <form action="{{ route('siswa.bulk-action') }}" method="POST" class="selection-actions">
        @csrf

        <input type="hidden" name="action" id="bulk-action-type">
        <div id="bulk-selected-inputs" class="bulk-hidden-inputs"></div>

        <button type="submit" class="btn btn-secondary" onclick="return submitBulkAction('keluarkan_kelas')">
            Keluarkan dari Kelas
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

                                    <form action="{{ route('siswa.keluarkan-kelas', $item->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Yakin ingin mengeluarkan siswa ini dari kelas?')">
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
        const bulkActionType = document.getElementById('bulk-action-type');
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

            if (selectAllSiswa) {
                selectAllSiswa.checked = false;
            }

            siswaCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });

            updateSelectedCount();
        }

        function getSelectedSiswaIds() {
            return Array.from(siswaCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);
        }

        function updateSelectedCount() {
            const selectedIds = getSelectedSiswaIds();

            selectedCountText.textContent = selectedIds.length + ' data dipilih';

            if (selectAllSiswa) {
                selectAllSiswa.checked = selectedIds.length === siswaCheckboxes.length && siswaCheckboxes.length > 0;
            }
        }

        if (selectAllSiswa) {
            selectAllSiswa.addEventListener('change', function () {
                siswaCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });

                updateSelectedCount();
            });
        }

        siswaCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        function submitBulkAction(action) {
            const selectedIds = getSelectedSiswaIds();

            if (selectedIds.length === 0) {
                alert('Pilih minimal satu data siswa terlebih dahulu.');
                return false;
            }

            const confirmKeluarkan = confirm(
                'Yakin ingin mengeluarkan ' + selectedIds.length + ' siswa dari kelas ini?'
            );

            if (!confirmKeluarkan) {
                return false;
            }

            bulkActionType.value = action;
            bulkSelectedInputs.innerHTML = '';

            selectedIds.forEach(id => {
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