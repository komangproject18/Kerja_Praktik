@extends('layouts.app')

@section('title', 'Daftar Kelas')
@section('page_title', 'Daftar Kelas')

@section('content')

    <div class="page-header">
        <div class="page-title">
            Daftar Kelas
        </div>

        <div class="page-subtitle">
            Kelola nama kelas, wali kelas, dan daftar siswa di setiap kelas.
        </div>
    </div>


    {{-- =====================================================
        ACTION
    ===================================================== --}}
    <div class="page-actions">

        <a
            href="{{ route('kelas.create') }}"
            class="btn btn-primary"
        >
            Tambah Kelas
        </a>

    </div>


    {{-- =====================================================
        DATA KELAS
    ===================================================== --}}
    <div class="table-card">

        <div class="table-header">
            Data Kelas
        </div>


        <div class="table-responsive">

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

                            {{-- NOMOR --}}
                            <td>
                                {{ $loop->iteration }}
                            </td>


                            {{-- NAMA KELAS --}}
                            <td>
                                {{ $item->nama_kelas }}
                            </td>


                            {{-- WALI KELAS --}}
                            <td>
                                {{ $item->wali_kelas ?? '-' }}
                            </td>


                            {{-- JUMLAH SISWA --}}
                            <td>
                                {{ $item->siswa_count }}
                            </td>


                            {{-- AKSI --}}
                            <td>

                                <div class="action-buttons">

                                    {{-- LIHAT SISWA --}}
                                    <a
                                        href="{{ route('kelas.show', $item->id) }}"
                                        class="btn btn-sm btn-info"
                                    >
                                        Lihat Siswa
                                    </a>


                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('kelas.edit', $item->id) }}"
                                        class="btn btn-sm btn-warning"
                                    >
                                        Edit
                                    </a>


                                    {{-- HAPUS --}}
                                    <form
                                        action="{{ route('kelas.destroy', $item->id) }}"
                                        method="POST"
                                        class="inline-form"

                                        data-confirm="Kelas {{ $item->nama_kelas }} akan dihapus. Pastikan data kelas ini memang sudah tidak diperlukan. Yakin ingin melanjutkan?"
                                        data-confirm-title="Hapus Kelas"
                                        data-confirm-button="Hapus"
                                        data-confirm-type="danger"
                                    >

                                        @csrf
                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="empty-data"
                            >
                                Belum ada data kelas.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection