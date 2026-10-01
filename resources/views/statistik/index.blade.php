@extends('layouts.app')

@section('title', 'Statistik Siswa')
@section('page_title', 'Statistik Siswa')

@section('content')

    <div class="page-header">
        <div class="page-title">
            Statistik Siswa
        </div>
    </div>


    {{-- RINGKASAN --}}
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-card-label">
                Total Siswa Aktif
            </div>

            <div class="stat-card-value">
                {{ $totalSiswa }}
            </div>

            <div class="stat-card-description">
                Seluruh siswa aktif
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-card-label">
                Laki-laki
            </div>

            <div class="stat-card-value">
                {{ $totalLakiLaki }}
            </div>

            <div class="stat-card-description">
                Siswa laki-laki aktif
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-card-label">
                Perempuan
            </div>

            <div class="stat-card-value">
                {{ $totalPerempuan }}
            </div>

            <div class="stat-card-description">
                Siswa perempuan aktif
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-card-label">
                Asal Sekolah
            </div>

            <div class="stat-card-value">
                {{ $jumlahAsalSekolah }}
            </div>

            <div class="stat-card-description">
                Jumlah sekolah asal
            </div>
        </div>

    </div>


    {{-- JENIS KELAMIN --}}
    <div class="stat-section">

        <div class="stat-section-header">
            <div>
                <div class="stat-section-title">
                    Statistik Jenis Kelamin
                </div>

                <div class="stat-section-subtitle">
                    Perbandingan siswa laki-laki dan perempuan.
                </div>
            </div>
        </div>


        <div class="gender-stat">

            @php
                $persenLaki = $totalSiswa > 0
                    ? round(($totalLakiLaki / $totalSiswa) * 100, 1)
                    : 0;

                $persenPerempuan = $totalSiswa > 0
                    ? round(($totalPerempuan / $totalSiswa) * 100, 1)
                    : 0;
            @endphp


            <div class="gender-item">

                <div class="gender-top">
                    <span>Laki-laki</span>

                    <strong>
                        {{ $totalLakiLaki }}
                        ({{ $persenLaki }}%)
                    </strong>
                </div>

                <div class="progress-track">
                    <div class="progress-bar" style="width: {{ $persenLaki }}%;"></div>
                </div>

            </div>


            <div class="gender-item">

                <div class="gender-top">
                    <span>Perempuan</span>

                    <strong>
                        {{ $totalPerempuan }}
                        ({{ $persenPerempuan }}%)
                    </strong>
                </div>

                <div class="progress-track">
                    <div class="progress-bar" style="width: {{ $persenPerempuan }}%;"></div>
                </div>

            </div>

        </div>
    </div>


    {{-- AGAMA --}}
    <div class="stat-section">

        <div class="stat-section-header">
            <div>
                <div class="stat-section-title">
                    Statistik Berdasarkan Agama
                </div>

                <div class="stat-section-subtitle">
                    Jumlah siswa laki-laki dan perempuan pada setiap agama.
                </div>
            </div>
        </div>


        <div class="table-responsive">

            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Agama</th>
                        <th>Laki-laki</th>
                        <th>Perempuan</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($statistikAgama as $item)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                <strong>
                                    {{ $item->agama }}
                                </strong>
                            </td>

                            <td>
                                {{ $item->laki_laki }}
                            </td>

                            <td>
                                {{ $item->perempuan }}
                            </td>

                            <td>
                                <span class="stat-total-badge">
                                    {{ $item->total }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="empty-data">
                                Belum ada data agama.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- ASAL SEKOLAH --}}
    <div class="stat-section">

        <div class="stat-section-header">

            <div>

                <div class="stat-section-title">
                    Statistik Berdasarkan Asal Sekolah
                </div>

                <div class="stat-section-subtitle">
                    Jumlah siswa berdasarkan sekolah asal dan jenis kelamin.
                </div>

            </div>

        </div>


        <div class="table-responsive">

            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Asal Sekolah</th>
                        <th>Laki-laki</th>
                        <th>Perempuan</th>
                        <th>Total</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse ($statistikAsalSekolah as $item)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                <strong>
                                    {{ $item->asal_sekolah }}
                                </strong>
                            </td>

                            <td>
                                {{ $item->laki_laki }}
                            </td>

                            <td>
                                {{ $item->perempuan }}
                            </td>

                            <td>
                                <span class="stat-total-badge">
                                    {{ $item->total }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="empty-data">
                                Belum ada data asal sekolah.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 22px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px;
        }

        .stat-card-label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 8px;
        }

        .stat-card-value {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
        }

        .stat-card-description {
            margin-top: 6px;
            font-size: 12px;
            color: #94a3b8;
        }


        .stat-section {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .stat-section-header {
            padding: 18px 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .stat-section-title {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
        }

        .stat-section-subtitle {
            margin-top: 4px;
            font-size: 13px;
            color: #64748b;
        }


        .gender-stat {
            padding: 20px;
            display: grid;
            gap: 20px;
        }

        .gender-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .progress-track {
            width: 100%;
            height: 9px;
            background: #e2e8f0;
            border-radius: 20px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: #2563eb;
            border-radius: 20px;
        }


        .stat-total-badge {
            display: inline-flex;
            min-width: 34px;
            justify-content: center;
            padding: 4px 8px;
            border-radius: 7px;
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: 700;
        }


        @media (max-width: 1000px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }


        @media (max-width: 600px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

@endsection