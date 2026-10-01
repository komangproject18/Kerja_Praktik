@extends('layouts.app')

@section('title', 'Daftar Kelas')
@section('page_title', 'Daftar Kelas')

@section('content')
    <div class="page-header">
        <div class="page-title">Daftar Kelas</div>
        <div class="page-subtitle">
            Kelola nama kelas, wali kelas, dan daftar siswa di setiap kelas.
        </div>
    </div>

    <div class="page-actions">
        <a href="{{ route('kelas.create') }}" class="btn btn-primary">
            Tambah Kelas
        </a>
    </div>

    <div class="table-card">
        <div class="table-header">
            Data Kelas
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kelas</th>
                    <th>Wali Kelas</th>
                    <th>Jumlah Siswa</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($kelas as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_kelas }}</td>
                        <td>{{ $item->wali_kelas ?? '-' }}</td>
                        <td>{{ $item->siswa_count }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('kelas.show', $item->id) }}" class="btn btn-sm btn-info">
                                    Lihat Siswa
                                </a>

                                <a href="{{ route('kelas.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('kelas.destroy', $item->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
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
                        <td colspan="5" class="empty-data">
                            Belum ada data kelas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection