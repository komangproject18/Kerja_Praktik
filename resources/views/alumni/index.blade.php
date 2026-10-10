@extends('layouts.app')

@section('title', 'Alumni')
@section('page_title', 'Alumni')

@section('content')

    <div class="page-header">

        <div class="page-title">
            Data Alumni
        </div>

        <div class="page-subtitle">
            Arsip siswa yang telah menyelesaikan pendidikan.
        </div>

    </div>


    {{-- FILTER --}}
    <div class="form-card">

        <div class="form-section-title">
            Cari Alumni
        </div>

        <form action="{{ route('alumni.index') }}" method="GET">

            <div class="form-grid">

                <div class="form-group">

                    <label class="form-label">
                        Cari
                    </label>

                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Nama, NIK, NIS atau NISN...">

                </div>


                <div class="form-group">

                    <label class="form-label">
                        Tahun Lulus
                    </label>

                    <select name="tahun_lulus_id" class="form-control">

                        <option value="">
                            Semua Tahun Lulus
                        </option>

                        @foreach ($tahunAjaran as $tahun)

                            <option value="{{ $tahun->id }}" {{ request('tahun_lulus_id') == $tahun->id ? 'selected' : '' }}>
                                {{ $tahun->nama_tahun_ajaran }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            <div class="form-footer" style="
                        margin-top: 18px;
                        display: flex;
                        gap: 8px;
                        flex-wrap: wrap;
                    ">

                <button type="submit" class="btn btn-primary">
                    Terapkan Filter
                </button>


                <a href="{{ route('alumni.index') }}" class="btn btn-secondary">
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- INFORMASI JUMLAH --}}
    <div style="
                margin-top: 18px;
                margin-bottom: 12px;
                color: #64748b;
                font-size: 14px;
            ">
        Menampilkan
        <strong>{{ $alumni->count() }}</strong>
        data alumni.
    </div>


    {{-- ALUMNI PER TAHUN --}}
    @forelse ($alumniPerTahun as $tahunId => $dataAlumni)

        @php
            $dataPertama = $dataAlumni->first();

            $namaTahunLulus =
                $dataPertama->tahunLulus
                    ->nama_tahun_ajaran
                ?? 'Tahun Lulus Tidak Tercatat';
        @endphp


        <div class="table-card" style="margin-bottom: 20px;">

            <div class="table-header table-header-action">

                <span>
                    Tahun Lulus {{ $namaTahunLulus }}
                </span>

                <span style="
                                font-size: 13px;
                                font-weight: 600;
                                color: #64748b;
                            ">
                    {{ $dataAlumni->count() }} Alumni
                </span>

            </div>


            <div class="table-responsive">

                <table>

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Nama Alumni</th>
                            <th>NIK</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Jenis Kelamin</th>
                            <th>Kelas Terakhir</th>
                            <th>Tahun Lulus</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach ($dataAlumni as $item)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>
                                        <strong>
                                            {{ $item->nama }}
                                        </strong>
                                    </td>


                                    <td>
                                        {{ $item->nik ?? '-' }}
                                    </td>


                                    <td>
                                        {{ $item->nis ?? '-' }}
                                    </td>


                                    <td>
                                        {{ $item->nisn ?? '-' }}
                                    </td>


                                    <td>
                                        {{ $item->jenis_kelamin ?? '-' }}
                                    </td>


                                    <td>
                                        {{
                            $item
                                ->riwayatKelasTerakhir
                                ?->kelas
                                    ?->nama_kelas
                            ?? '-'
                                                    }}
                                    </td>


                                    <td>
                                        {{
                            $item
                                ->tahunLulus
                                    ?->nama_tahun_ajaran
                            ?? '-'
                                                    }}
                                    </td>


                                    <td>

                                        <a href="{{ route('siswa.show', $item->id) }}" class="btn btn-sm btn-info">
                                            Detail
                                        </a>

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
                Belum ada data alumni yang sesuai.
            </div>

        </div>

    @endforelse

@endsection