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

    <div class="table-card">
        <div class="table-header">
            Daftar Siswa Kelas {{ $kelas->nama_kelas }}
        </div>

        <table>
            <thead>
                <tr>
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
                @forelse ($kelas->siswa as $item)
                    <tr>
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
                        <td colspan="7" class="empty-data">
                            Belum ada siswa di kelas ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection