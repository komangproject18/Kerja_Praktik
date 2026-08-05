@extends('layouts.app')

@section('title', 'Edit Siswa')
@section('page_title', 'Edit Siswa')

@section('content')
    <div class="page-header">
        <div class="page-title">Edit Data Siswa</div>
        <div class="page-subtitle">
            Perbarui data siswa, data orang tua, dan data wali.
        </div>
    </div>

    @error('penanggung_jawab')
        <div class="alert alert-error">
            {{ $message }}
        </div>
    @enderror

    <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- DATA SISWA --}}
        <div class="form-card">
            <div class="form-section-title">Data Siswa</div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Siswa</label>
                    <input type="text" name="nama" class="form-control"
                        value="{{ old('nama', $siswa->nama) }}"
                        placeholder="Masukkan nama siswa">

                    @error('nama')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">NIS</label>
                    <input type="text" name="nis" class="form-control"
                        value="{{ old('nis', $siswa->nis) }}"
                        placeholder="Masukkan NIS">

                    @error('nis')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" class="form-control"
                        value="{{ old('nisn', $siswa->nisn) }}"
                        placeholder="Masukkan NISN">

                    @error('nisn')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">Pilih jenis kelamin</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki
                        </option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan
                        </option>
                    </select>

                    @error('jenis_kelamin')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control"
                        value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}"
                        placeholder="Contoh: Palembang">

                    @error('tempat_lahir')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control"
                        value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}">

                    @error('tanggal_lahir')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Agama</label>
                    <select name="agama" class="form-control">
                        <option value="">Pilih agama</option>
                        @foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama)
                            <option value="{{ $agama }}" {{ old('agama', $siswa->agama) == $agama ? 'selected' : '' }}>
                                {{ $agama }}
                            </option>
                        @endforeach
                    </select>

                    @error('agama')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Status Anak</label>
                    <select name="status_anak" class="form-control">
                        <option value="">Pilih status anak</option>
                        @foreach (['Anak Kandung', 'Anak Angkat'] as $status)
                            <option value="{{ $status }}" {{ old('status_anak', $siswa->status_anak) == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>

                    @error('status_anak')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Anak Ke</label>
                    <input type="text" name="anak_ke" class="form-control"
                    value="{{ old('anak_ke', $siswa->anak_ke) }}"
                    placeholder="Contoh: Pertama">

                    @error('anak_ke')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">No HP Siswa</label>
                    <input type="text" name="no_hp" class="form-control"
                        value="{{ old('no_hp', $siswa->no_hp) }}"
                        placeholder="Contoh: 081234567890">

                    @error('no_hp')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Asal Sekolah</label>
                    <input type="text" name="asal_sekolah" class="form-control"
                        value="{{ old('asal_sekolah', $siswa->asal_sekolah) }}"
                        placeholder="Contoh: SMP Negeri 1">

                    @error('asal_sekolah')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Diterima di Kelas</label>
                    <select name="kelas_id" class="form-control">
                        <option value="">Pilih kelas</option>
                        @foreach ($kelas as $item)
                            <option value="{{ $item->id }}" {{ old('kelas_id', $siswa->kelas_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_kelas }}
                            </option>
                        @endforeach
                    </select>

                    @error('kelas_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Diterima</label>
                    <input type="date" name="tanggal_diterima" class="form-control"
                        value="{{ old('tanggal_diterima', $siswa->tanggal_diterima) }}">

                    @error('tanggal_diterima')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group form-full">
                    <label class="form-label">Alamat Siswa</label>
                    <textarea name="alamat" class="form-control" placeholder="Masukkan alamat siswa">{{ old('alamat', $siswa->alamat) }}</textarea>

                    @error('alamat')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- DATA ORANG TUA --}}
        <div class="form-card">
            <div class="form-section-title">Data Orang Tua</div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Ayah</label>
                    <input type="text" name="nama_ayah" class="form-control"
                        value="{{ old('nama_ayah', $siswa->orangTua->nama_ayah ?? '') }}"
                        placeholder="Masukkan nama ayah">

                    @error('nama_ayah')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Ibu</label>
                    <input type="text" name="nama_ibu" class="form-control"
                        value="{{ old('nama_ibu', $siswa->orangTua->nama_ibu ?? '') }}"
                        placeholder="Masukkan nama ibu">

                    @error('nama_ibu')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">No HP Orang Tua</label>
                    <input type="text" name="no_hp_orang_tua" class="form-control"
                        value="{{ old('no_hp_orang_tua', $siswa->orangTua->no_hp_orang_tua ?? '') }}"
                        placeholder="Contoh: 081234567890">

                    @error('no_hp_orang_tua')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Pekerjaan Ayah</label>
                    <input type="text" name="pekerjaan_ayah" class="form-control"
                        value="{{ old('pekerjaan_ayah', $siswa->orangTua->pekerjaan_ayah ?? '') }}"
                        placeholder="Masukkan pekerjaan ayah">

                    @error('pekerjaan_ayah')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Pekerjaan Ibu</label>
                    <input type="text" name="pekerjaan_ibu" class="form-control"
                        value="{{ old('pekerjaan_ibu', $siswa->orangTua->pekerjaan_ibu ?? '') }}"
                        placeholder="Masukkan pekerjaan ibu">

                    @error('pekerjaan_ibu')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group form-full">
                    <label class="form-label">Alamat Orang Tua</label>
                    <textarea name="alamat_orang_tua" class="form-control" placeholder="Masukkan alamat orang tua">{{ old('alamat_orang_tua', $siswa->orangTua->alamat_orang_tua ?? '') }}</textarea>

                    @error('alamat_orang_tua')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- DATA WALI --}}
        <div class="form-card">
            <div class="form-section-title">Data Wali</div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Wali</label>
                    <input type="text" name="nama_wali" class="form-control"
                        value="{{ old('nama_wali', $siswa->wali->nama_wali ?? '') }}"
                        placeholder="Masukkan nama wali">

                    @error('nama_wali')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">No HP Wali</label>
                    <input type="text" name="no_hp_wali" class="form-control"
                        value="{{ old('no_hp_wali', $siswa->wali->no_hp_wali ?? '') }}"
                        placeholder="Contoh: 081234567890">

                    @error('no_hp_wali')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Pekerjaan Wali</label>
                    <input type="text" name="pekerjaan_wali" class="form-control"
                        value="{{ old('pekerjaan_wali', $siswa->wali->pekerjaan_wali ?? '') }}"
                        placeholder="Masukkan pekerjaan wali">

                    @error('pekerjaan_wali')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group form-full">
                    <label class="form-label">Alamat Wali</label>
                    <textarea name="alamat_wali" class="form-control" placeholder="Masukkan alamat wali">{{ old('alamat_wali', $siswa->wali->alamat_wali ?? '') }}</textarea>

                    @error('alamat_wali')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>

            <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
@endsection