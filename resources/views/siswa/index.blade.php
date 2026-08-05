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
                    <input
                        type="text"
                        name="search"
                        id="search"
                        class="form-control"
                        placeholder="Cari nama, NIS, atau NISN"
                        value="{{ request('search') }}"
                    >
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

    <div class="table-card">
        <div class="table-header">
            Daftar Siswa
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
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
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama }}</td>
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

                            <form action="{{ route('siswa.destroy', $item->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Yakin ingin menghapus data siswa ini?')">
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
                        <td colspan="9" class="empty-data">
                            Belum ada data siswa.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection