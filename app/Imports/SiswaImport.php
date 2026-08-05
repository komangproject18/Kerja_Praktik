<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Wali;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SiswaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $dataImport = [];
        $errors = [];

        foreach ($rows as $index => $row) {
            $baris = $index + 2;

            if (!$this->barisBerisiData($row)) {
                continue;
            }

            $nama = $this->value($row, 'nama');
            $nis = $this->value($row, 'nis');
            $nisn = $this->value($row, 'nisn');

            $namaAyah = $this->value($row, 'nama_ayah');
            $namaIbu = $this->value($row, 'nama_ibu');
            $namaWali = $this->value($row, 'nama_wali');

            $adaOrangTua = $namaAyah || $namaIbu;
            $adaWali = $namaWali;

            if (!$nama) {
                $errors[] = "Baris {$baris}: nama siswa wajib diisi.";
            }

            if (!$adaOrangTua && !$adaWali) {
                $errors[] = "Baris {$baris}: data orang tua atau wali wajib diisi minimal salah satu.";
            }

            $jenisKelamin = $this->normalisasiJenisKelamin($this->value($row, 'jenis_kelamin'));

            if ($this->value($row, 'jenis_kelamin') && !$jenisKelamin) {
                $errors[] = "Baris {$baris}: jenis kelamin harus Laki-laki atau Perempuan.";
            }

            $dataImport[] = [
                'siswa' => [
                    'nama' => $nama,
                    'nis' => $nis,
                    'nisn' => $nisn,
                    'tempat_lahir' => $this->value($row, 'tempat_lahir'),
                    'tanggal_lahir' => $this->formatTanggal($row['tanggal_lahir'] ?? null),
                    'jenis_kelamin' => $jenisKelamin,
                    'agama' => $this->value($row, 'agama'),
                    'status_anak' => $this->value($row, 'status_anak'),
                    'anak_ke' => $this->value($row, 'anak_ke'),
                    'alamat' => $this->value($row, 'alamat'),
                    'no_hp' => $this->value($row, 'no_hp'),
                    'asal_sekolah' => $this->value($row, 'asal_sekolah'),
                    'tanggal_diterima' => $this->formatTanggal($row['tanggal_diterima'] ?? null),
                ],

                'kelas' => $this->value($row, 'kelas'),

                'orang_tua' => [
                    'nama_ayah' => $namaAyah,
                    'nama_ibu' => $namaIbu,
                    'alamat_orang_tua' => $this->value($row, 'alamat_orang_tua'),
                    'no_hp_orang_tua' => $this->value($row, 'no_hp_orang_tua'),
                    'pekerjaan_ayah' => $this->value($row, 'pekerjaan_ayah'),
                    'pekerjaan_ibu' => $this->value($row, 'pekerjaan_ibu'),
                ],

                'wali' => [
                    'nama_wali' => $namaWali,
                    'alamat_wali' => $this->value($row, 'alamat_wali'),
                    'no_hp_wali' => $this->value($row, 'no_hp_wali'),
                    'pekerjaan_wali' => $this->value($row, 'pekerjaan_wali'),
                ],
            ];
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages([
                'file' => $errors,
            ]);
        }

        DB::transaction(function () use ($dataImport) {
            foreach ($dataImport as $data) {
                $kelasId = null;

                if ($data['kelas']) {
                    $kelas = Kelas::firstOrCreate(
                        ['nama_kelas' => $data['kelas']],
                        ['wali_kelas' => null]
                    );

                    $kelasId = $kelas->id;
                }

                $dataSiswa = array_merge($data['siswa'], [
                    'kelas_id' => $kelasId,
                ]);

                $siswa = null;

                if (!empty($dataSiswa['nisn'])) {
                    $siswa = Siswa::where('nisn', $dataSiswa['nisn'])->first();
                }

                if (!$siswa && !empty($dataSiswa['nis'])) {
                    $siswa = Siswa::where('nis', $dataSiswa['nis'])->first();
                }

                if ($siswa) {
                    $siswa->update($dataSiswa);
                } else {
                    $siswa = Siswa::create($dataSiswa);
                }

                $adaOrangTua = collect($data['orang_tua'])->filter()->isNotEmpty();
                $adaWali = collect($data['wali'])->filter()->isNotEmpty();

                if ($adaOrangTua) {
                    OrangTua::updateOrCreate(
                        ['siswa_id' => $siswa->id],
                        $data['orang_tua']
                    );
                }

                if ($adaWali) {
                    Wali::updateOrCreate(
                        ['siswa_id' => $siswa->id],
                        $data['wali']
                    );
                }
            }
        });
    }

    private function value($row, $key)
    {
        $value = $row[$key] ?? null;

        if ($value === null || $value === '') {
            return null;
        }

        return trim((string) $value);
    }

    private function formatTanggal($value)
    {
        if (!$value) {
            return null;
        }

        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function normalisasiJenisKelamin($value)
    {
        if (!$value) {
            return null;
        }

        $value = strtolower(trim($value));

        if (in_array($value, ['l', 'laki-laki', 'laki laki', 'pria'])) {
            return 'Laki-laki';
        }

        if (in_array($value, ['p', 'perempuan', 'wanita'])) {
            return 'Perempuan';
        }

        return null;
    }

    private function barisBerisiData($row)
    {
        foreach ($row as $value) {
            if ($value !== null && $value !== '') {
                return true;
            }
        }

        return false;
    }
}