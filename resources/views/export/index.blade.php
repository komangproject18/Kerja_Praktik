@extends('layouts.app')

@section('title', 'Export Excel')
@section('page_title', 'Export Excel')

@section('content')
    <div class="page-header">
        <div class="page-title">Export Data Siswa</div>
        <div class="page-subtitle">
            Unduh data siswa lengkap dalam format Excel untuk laporan dan kebutuhan cetak.
        </div>
    </div>

    <div class="form-card">
        <div class="form-section-title">Export Semua Data Siswa</div>

        <p style="font-size: 14px; color: #64748b; margin-bottom: 18px;">
            File Excel akan berisi data siswa, data kelas, data orang tua, dan data wali.
        </p>

        <a href="{{ route('export.siswa') }}" class="btn btn-primary">
            Export Semua Data
        </a>
    </div>

    <div class="form-card">
        <div class="form-section-title">Export Berdasarkan Kelas</div>

        <form action="{{ route('export.siswa') }}" method="GET">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Pilih Kelas</label>
                    <select name="kelas_id" class="form-control">
                        <option value="">Pilih kelas</option>
                        @foreach ($kelas as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="display: flex; align-items: end;">
                    <button type="submit" class="btn btn-primary">
                        Export Per Kelas
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection