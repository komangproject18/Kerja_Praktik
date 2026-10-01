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

    <div class="page-actions">
        <a href="{{ url('/siswa/create') }}" class="btn btn-primary">
            Tambah Siswa
        </a>
    </div>

    <div class="form-card" style="margin-bottom: 20px;">
        <form action="{{ route('siswa.index') }}" method="GET">
            <div style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 12px; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="search" class="form-label">Cari Siswa</label>
                    <input type="text" name="search" id="search" class="form-control"
                        placeholder="Cari nama, NIS, atau NISN" value="{{ request('search') }}">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="kelas_id" class="form-label">Filter Kelas</label>
                    <select name="kelas_id" id="kelas_id" class="form-control">
                        <option value="">Semua Kelas</option>

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

    <div id="bulk-page" class="table-card">
        <div class="table-header table-header-action">
            <span>Daftar Siswa</span>

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

                <button type="submit" class="btn btn-danger" onclick="return submitBulkAction('hapus')">
                    Hapus
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
                            <td class="checkbox-column select-cell">
                                <input type="checkbox" class="table-checkbox siswa-checkbox" value="{{ $item->id }}">
                            </td>

                            <td>{{ $loop->iteration }}</td>

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
                                    ">
                                    Tidak ada
                                    </div>
                                @endif
                            </td>

                            <td>{{ $item->nama }}</td>

                            <td>{{ $item->nik ?? '-' }}</td>

                            <td>{{ $item->nis ?? '-' }}</td>

                            <td>{{ $item->nisn ?? '-' }}</td>

                            <td>{{ $item->jenis_kelamin ?? '-' }}</td>

                            <td>
                                @if ($item->kelas)
                                    <span class="badge">{{ $item->kelas->nama_kelas }}</span>
                                @else
                                    -
                                @endif
                            </td>

                            <td>{{ $item->asal_sekolah ?? '-' }}</td>

                            <td>
                                {{ $item->tanggal_diterima ? date('d-m-Y', strtotime($item->tanggal_diterima)) : '-' }}
                            </td>

                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('siswa.show', $item->id) }}" class="btn btn-sm btn-info">
                                        Detail
                                    </a>

                                    <a href="{{ route('siswa.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('siswa.destroy', $item->id) }}" method="POST" class="inline-form"
                                        onsubmit="return confirm('Yakin ingin menghapus data siswa ini?')">
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

            const confirmDelete = confirm(
                'Yakin ingin menghapus ' + selectedIds.length + ' data siswa secara permanen?'
            );

            if (!confirmDelete) {
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