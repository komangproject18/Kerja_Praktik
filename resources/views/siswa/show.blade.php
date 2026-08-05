@extends('layouts.app')

@section('title', 'Detail Siswa')
@section('page_title', 'Detail Siswa')

@section('content')
    <div class="page-header">
        <div class="page-title">{{ $siswa->nama }}</div>
        <div class="page-subtitle">
            Detail data siswa, orang tua, dan wali.
        </div>
    </div>

    <div class="action-row">
        <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Kembali</a>
        <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-warning">Edit Siswa</a>
    </div>

    <div class="form-card">
        <div class="form-section-title">Data Siswa</div>

        <table>
            <tr><th>Nama</th><td>{{ $siswa->nama }}</td></tr>
            <tr><th>NIS</th><td>{{ $siswa->nis ?? '-' }}</td></tr>
            <tr><th>NISN</th><td>{{ $siswa->nisn ?? '-' }}</td></tr>
            <tr><th>Tempat, Tanggal Lahir</th><td>{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? date('d-m-Y', strtotime($siswa->tanggal_lahir)) : '-' }}</td></tr>
            <tr><th>Jenis Kelamin</th><td>{{ $siswa->jenis_kelamin ?? '-' }}</td></tr>
            <tr><th>Agama</th><td>{{ $siswa->agama ?? '-' }}</td></tr>
            <tr><th>Status Anak</th><td>{{ $siswa->status_anak ?? '-' }}</td></tr>
            <tr><th>Anak Ke</th><td>{{ $siswa->anak_ke ?? '-' }}</td></tr>
            <tr><th>Alamat</th><td>{{ $siswa->alamat ?? '-' }}</td></tr>
            <tr><th>No HP</th><td>{{ $siswa->no_hp ?? '-' }}</td></tr>
            <tr><th>Asal Sekolah</th><td>{{ $siswa->asal_sekolah ?? '-' }}</td></tr>
            <tr><th>Diterima di Kelas</th><td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td></tr>
            <tr><th>Tanggal Diterima</th><td>{{ $siswa->tanggal_diterima ? date('d-m-Y', strtotime($siswa->tanggal_diterima)) : '-' }}</td></tr>
        </table>
    </div>

    <div class="form-card">
        <div class="form-section-title">Data Orang Tua</div>

        <table>
            <tr><th>Nama Ayah</th><td>{{ $siswa->orangTua->nama_ayah ?? '-' }}</td></tr>
            <tr><th>Nama Ibu</th><td>{{ $siswa->orangTua->nama_ibu ?? '-' }}</td></tr>
            <tr><th>Alamat Orang Tua</th><td>{{ $siswa->orangTua->alamat_orang_tua ?? '-' }}</td></tr>
            <tr><th>No HP Orang Tua</th><td>{{ $siswa->orangTua->no_hp_orang_tua ?? '-' }}</td></tr>
            <tr><th>Pekerjaan Ayah</th><td>{{ $siswa->orangTua->pekerjaan_ayah ?? '-' }}</td></tr>
            <tr><th>Pekerjaan Ibu</th><td>{{ $siswa->orangTua->pekerjaan_ibu ?? '-' }}</td></tr>
        </table>
    </div>

    <div class="form-card">
        <div class="form-section-title">Data Wali</div>

        <table>
            <tr><th>Nama Wali</th><td>{{ $siswa->wali->nama_wali ?? '-' }}</td></tr>
            <tr><th>Alamat Wali</th><td>{{ $siswa->wali->alamat_wali ?? '-' }}</td></tr>
            <tr><th>No HP Wali</th><td>{{ $siswa->wali->no_hp_wali ?? '-' }}</td></tr>
            <tr><th>Pekerjaan Wali</th><td>{{ $siswa->wali->pekerjaan_wali ?? '-' }}</td></tr>
        </table>
    </div>
@endsection