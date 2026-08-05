@extends('layouts.app')

@section('title', 'Beranda')
@section('page_title', 'Beranda')

@section('content')
    <!-- <div class="page-header">
        <div class="page-title">Dashboard Tata Usaha</div>
        <div class="page-subtitle">
            Akses cepat untuk mengelola data siswa, daftar kelas, dan laporan Excel.
        </div>
    </div> -->

    <div class="card-grid">
        <div class="card">
            <div class="card-label">Total Siswa</div>
            <div class="card-value">{{ $totalSiswa }}</div>
        </div>

        <div class="card">
            <div class="card-label">Total Kelas</div>
            <div class="card-value">{{ $totalKelas }}</div>
        </div>

        <div class="card">
            <div class="card-label">Laki-laki</div>
            <div class="card-value">{{ $totalLakiLaki }}</div>
        </div>

        <div class="card">
            <div class="card-label">Perempuan</div>
            <div class="card-value">{{ $totalPerempuan }}</div>
        </div>
    </div>

    <div class="action-row">
        <a href="{{ url('/siswa/create') }}" class="btn btn-primary">Tambah Siswa</a>
        <a href="{{ url('/kelas') }}" class="btn btn-secondary">Lihat Daftar Kelas</a>
        <a href="{{ url('/export') }}" class="btn btn-secondary">Export Excel</a>
    </div>

    <div class="table-card">
        <div class="table-header">
            Data Siswa Terbaru
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIS</th>
                    <th>NISN</th>
                    <th>Kelas</th>
                    <th>Tanggal Diterima</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($siswaTerbaru as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->nis ?? '-' }}</td>
                        <td>{{ $item->nisn ?? '-' }}</td>
                        <td>
                            @if ($item->kelas)
                                <span class="badge">{{ $item->kelas->nama_kelas }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            {{ $item->tanggal_diterima ? date('d-m-Y', strtotime($item->tanggal_diterima)) : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-data">
                            Belum ada data siswa.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection