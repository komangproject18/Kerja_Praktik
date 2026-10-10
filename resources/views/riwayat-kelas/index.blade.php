@extends('layouts.app')

@section('title', 'Riwayat Kelas')
@section('page_title', 'Riwayat Kelas')

@section('content')

    {{-- =====================================================
    HEADER
    ===================================================== --}}
    <div class="page-header">

        <div class="page-title">
            Riwayat Kelas
        </div>

        <div class="page-subtitle">
            Arsip data kelas siswa berdasarkan tahun ajaran.
        </div>

    </div>


    {{-- =====================================================
    FILTER RIWAYAT
    ===================================================== --}}
    <div class="form-card">

        <div class="form-section-title">
            Filter Riwayat
        </div>


        <form action="{{ route('riwayat-kelas.index') }}" method="GET">

            <div class="form-grid">


                {{-- CARI SISWA --}}
                <div class="form-group">

                    <label class="form-label">
                        Cari Siswa
                    </label>

                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Nama, NIK, NIS atau NISN...">

                </div>


                {{-- DARI TAHUN AJARAN --}}
                <div class="form-group">

                    <label class="form-label">
                        Dari Tahun Ajaran
                    </label>

                    <select name="tahun_dari_id" class="form-control">

                        <option value="">
                            Pilih Tahun
                        </option>

                        @foreach ($tahunAjaran as $tahun)

                            <option value="{{ $tahun->id }}" {{ request('tahun_dari_id') == $tahun->id ? 'selected' : '' }}>
                                {{ $tahun->nama_tahun_ajaran }}

                                @if ($tahun->status_aktif)
                                    - Aktif
                                @endif
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- SAMPAI TAHUN AJARAN --}}
                <div class="form-group">

                    <label class="form-label">
                        Sampai Tahun Ajaran
                    </label>

                    <select name="tahun_sampai_id" class="form-control">

                        <option value="">
                            Pilih Tahun
                        </option>

                        @foreach ($tahunAjaran as $tahun)

                            <option value="{{ $tahun->id }}" {{ request('tahun_sampai_id') == $tahun->id ? 'selected' : '' }}>
                                {{ $tahun->nama_tahun_ajaran }}

                                @if ($tahun->status_aktif)
                                    - Aktif
                                @endif
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- KELAS --}}
                <div class="form-group">

                    <label class="form-label">
                        Kelas
                    </label>

                    <select name="kelas_id" class="form-control">

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


                {{-- JENIS RIWAYAT --}}
                <div class="form-group">

                    <label class="form-label">
                        Jenis Riwayat
                    </label>

                    <select name="keterangan" class="form-control">

                        <option value="">
                            Semua Riwayat
                        </option>

                        @foreach ($jenisRiwayat as $jenis)

                            <option value="{{ $jenis }}" {{ request('keterangan') == $jenis ? 'selected' : '' }}>
                                {{ $jenis }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- =================================================
            ACTION FILTER
            ================================================= --}}
            <div class="form-footer" style="
                        margin-top: 18px;
                        display: flex;
                        gap: 8px;
                        flex-wrap: wrap;
                    ">

                {{-- TERAPKAN FILTER --}}
                <button type="submit" class="btn btn-primary">
                    Terapkan Filter
                </button>


                {{-- RESET --}}
                <a href="{{ route('riwayat-kelas.index') }}" class="btn btn-secondary">
                    Reset
                </a>


                {{-- =================================================
                EXPORT EXCEL RIWAYAT KELAS
                ================================================= --}}

                @if (
                                request('tahun_dari_id') ||
                                request('tahun_sampai_id')
                            )

                            <a href="{{ route(
                        'riwayat-kelas.export',
                        request()->only([
                            'search',
                            'tahun_dari_id',
                            'tahun_sampai_id',
                            'kelas_id',
                            'keterangan',
                        ])
                    ) }}" class="btn btn-success">
                                Export Excel
                            </a>

                @else

                    <button type="button" class="btn btn-secondary" onclick="showToast(
                                    'Pilih minimal satu tahun ajaran terlebih dahulu sebelum export.',
                                    'warning'
                                )">
                        Export Excel
                    </button>

                @endif

            </div>

        </form>

    </div>


    {{-- =====================================================
    JUMLAH DATA
    ===================================================== --}}
    <div style="
                margin-top: 18px;
                margin-bottom: 12px;
                font-size: 14px;
                color: #64748b;
            ">

        Menampilkan
        <strong>{{ $riwayat->count() }}</strong>
        data riwayat.

    </div>


    {{-- =====================================================
    RIWAYAT PER TAHUN AJARAN
    ===================================================== --}}
    @forelse ($riwayatPerTahun as $namaTahun => $dataRiwayat)

        <div class="table-card" style="margin-bottom: 20px;">

            {{-- HEADER TAHUN AJARAN --}}
            <div class="table-header table-header-action">

                <span>
                    Tahun Ajaran {{ $namaTahun }}
                </span>


                <span style="
                                font-size: 13px;
                                font-weight: 600;
                                color: #64748b;
                            ">
                    {{ $dataRiwayat->count() }} Data
                </span>

            </div>


            {{-- =================================================
            TABEL
            ================================================= --}}
            <div class="table-responsive">

                <table>

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>NIK</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Kelas</th>
                            <th>Keterangan</th>
                            <th>Status Saat Ini</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach ($dataRiwayat as $item)

                            <tr>

                                {{-- NOMOR --}}
                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                {{-- NAMA SISWA --}}
                                <td>

                                    <strong>
                                        {{ $item->siswa->nama ?? '-' }}
                                    </strong>

                                </td>


                                {{-- NIK --}}
                                <td>
                                    {{ $item->siswa->nik ?? '-' }}
                                </td>


                                {{-- NIS --}}
                                <td>
                                    {{ $item->siswa->nis ?? '-' }}
                                </td>


                                {{-- NISN --}}
                                <td>
                                    {{ $item->siswa->nisn ?? '-' }}
                                </td>


                                {{-- KELAS --}}
                                <td>
                                    {{ $item->kelas->nama_kelas ?? '-' }}
                                </td>


                                {{-- KETERANGAN --}}
                                <td>
                                    {{ $item->keterangan ?? '-' }}
                                </td>


                                {{-- STATUS SAAT INI --}}
                                <td>
                                    {{ $item->siswa->status_siswa ?? '-' }}
                                </td>


                                {{-- AKSI --}}
                                <td>

                                    @if ($item->siswa)

                                        <a href="{{ route('siswa.show', $item->siswa->id) }}" class="btn btn-sm btn-info">
                                            Detail
                                        </a>

                                    @else

                                        -

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>


    @empty

        <div class="table-card">

            <div class="empty-data">
                Belum ada riwayat kelas yang sesuai dengan filter.
            </div>

        </div>

    @endforelse

@endsection