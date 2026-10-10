@extends('layouts.app')

@section('title', 'Tahun Ajaran')
@section('page_title', 'Tahun Ajaran')

@section('content')

    <div class="page-header">

        <div class="page-title">
            Tahun Ajaran
        </div>

        <div class="page-subtitle">
            Kelola periode tahun ajaran yang digunakan dalam sistem.
        </div>

    </div>


    {{-- =====================================================
    ACTION
    ===================================================== --}}
    <div class="page-actions">

        <a href="{{ route('tahun-ajaran.create') }}" class="btn btn-primary">
            Tambah Tahun Ajaran
        </a>

    </div>


    {{-- =====================================================
    DAFTAR TAHUN AJARAN
    ===================================================== --}}
    <div class="table-card">

        <div class="table-header">
            Daftar Tahun Ajaran
        </div>


        <div class="table-responsive">

            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Tahun Ajaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse ($tahunAjaran as $item)

                        <tr>

                            {{-- NOMOR --}}
                            <td>
                                {{ $loop->iteration }}
                            </td>


                            {{-- TAHUN AJARAN --}}
                            <td>

                                <strong>
                                    {{ $item->nama_tahun_ajaran }}
                                </strong>

                            </td>


                            {{-- STATUS --}}
                            <td>

                                @if ($item->status_aktif)

                                    <span class="badge">
                                        Aktif
                                    </span>

                                @else

                                    <span style="
                                                        display: inline-block;
                                                        padding: 4px 9px;
                                                        border-radius: 7px;
                                                        background: #f1f5f9;
                                                        color: #64748b;
                                                        font-size: 12px;
                                                        font-weight: 600;
                                                    ">
                                        Tidak Aktif
                                    </span>

                                @endif

                            </td>


                            {{-- AKSI --}}
                            <td>

                                <div class="action-buttons">


                                    {{-- ============================
                                    AKTIFKAN
                                    ============================ --}}
                                    @if (!$item->status_aktif)

                                        <form action="{{ route('tahun-ajaran.activate', $item->id) }}" method="POST"
                                            class="inline-form"
                                            data-confirm="Tahun ajaran {{ $item->nama_tahun_ajaran }} akan dijadikan tahun ajaran aktif. Tahun ajaran yang sedang aktif akan dinonaktifkan secara otomatis. Yakin ingin melanjutkan?"
                                            data-confirm-title="Aktifkan Tahun Ajaran" data-confirm-button="Aktifkan"
                                            data-confirm-type="info">

                                            @csrf
                                            @method('PATCH')


                                            <button type="submit" class="btn btn-sm btn-primary">
                                                Aktifkan
                                            </button>

                                        </form>

                                    @endif



                                    {{-- ============================
                                    EDIT
                                    ============================ --}}
                                    <a href="{{ route('tahun-ajaran.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                        Edit
                                    </a>



                                    {{-- ============================
                                    HAPUS
                                    ============================ --}}
                                    @if (!$item->status_aktif)

                                        <form action="{{ route('tahun-ajaran.destroy', $item->id) }}" method="POST"
                                            class="inline-form"
                                            data-confirm="Tahun ajaran {{ $item->nama_tahun_ajaran }} akan dihapus. Pastikan tahun ajaran ini sudah tidak diperlukan dan tidak digunakan pada data penting. Yakin ingin melanjutkan?"
                                            data-confirm-title="Hapus Tahun Ajaran" data-confirm-button="Hapus"
                                            data-confirm-type="danger">

                                            @csrf
                                            @method('DELETE')


                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Hapus
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="4" class="empty-data">
                                Belum ada tahun ajaran.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection