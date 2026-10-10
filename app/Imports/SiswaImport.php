<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Wali;
use App\Models\TahunAjaran;
use App\Models\RiwayatKelas;
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
        $nikDalamFile = [];

        foreach ($rows as $index => $row) {
            $baris = $index + 2;

            if (!$this->barisBerisiData($row)) {
                continue;
            }

            $nama = $this->value($row, 'nama');
            $nik = $this->value($row, 'nik');
            $nis = $this->value($row, 'nis');
            $nisn = $this->value($row, 'nisn');

            $namaAyah = $this->value($row, 'nama_ayah');
            $namaIbu = $this->value($row, 'nama_ibu');
            $namaWali = $this->value($row, 'nama_wali');

            $adaOrangTua = $namaAyah || $namaIbu;
            $adaWali = $namaWali;

            // =========================
            // VALIDASI NAMA
            // =========================
            if (!$nama) {
                $errors[] = "Baris {$baris}: nama siswa wajib diisi.";
            } elseif (strlen($nama) < 3) {
                $errors[] = "Baris {$baris}: nama siswa minimal 3 karakter.";
            }

            // =========================
            // VALIDASI NIK
            // =========================
            if (!$nik) {
                $errors[] = "Baris {$baris}: NIK wajib diisi.";
            } else {
                if (in_array($nik, $nikDalamFile)) {
                    $errors[] = "Baris {$baris}: NIK {$nik} duplikat di dalam file Excel.";
                } else {
                    $nikDalamFile[] = $nik;
                }
            }

            // =========================
            // VALIDASI NIS
            // =========================
            if ($nis && !preg_match('/^[0-9.]+$/', $nis)) {
                $errors[] = "Baris {$baris}: NIS hanya boleh berisi angka dan titik (contoh: 2047.26).";
            }

            if ($nis && strlen($nis) < 4) {
                $errors[] = "Baris {$baris}: NIS minimal 4 digit.";
            }

            // =========================
            // VALIDASI NISN
            // =========================
            if ($nisn && !preg_match('/^[0-9]+$/', $nisn)) {
                $errors[] = "Baris {$baris}: NISN hanya boleh berisi angka.";
            }

            if ($nisn && strlen($nisn) != 10) {
                $errors[] = "Baris {$baris}: NISN harus 10 digit.";
            }

            // =========================
            // VALIDASI NO HP SISWA
            // =========================
            $noHp = $this->value($row, 'no_hp');

            if ($noHp && !preg_match('/^[0-9]+$/', $noHp)) {
                $errors[] = "Baris {$baris}: No HP siswa hanya boleh berisi angka.";
            }

            if ($noHp && (strlen($noHp) < 10 || strlen($noHp) > 15)) {
                $errors[] = "Baris {$baris}: No HP siswa harus 10-15 digit.";
            }

            // =========================
            // VALIDASI NO HP ORANG TUA
            // =========================
            $noHpOrangTua = $this->value($row, 'no_hp_orang_tua');

            if ($noHpOrangTua && !preg_match('/^[0-9]+$/', $noHpOrangTua)) {
                $errors[] = "Baris {$baris}: No HP orang tua hanya boleh berisi angka.";
            }

            // =========================
            // VALIDASI NO HP WALI
            // =========================
            $noHpWali = $this->value($row, 'no_hp_wali');

            if ($noHpWali && !preg_match('/^[0-9]+$/', $noHpWali)) {
                $errors[] = "Baris {$baris}: No HP wali hanya boleh berisi angka.";
            }

            // =========================
            // VALIDASI ORANG TUA / WALI
            // =========================
            if (!$adaOrangTua && !$adaWali) {
                $errors[] = "Baris {$baris}: data orang tua atau wali wajib diisi minimal salah satu.";
            }

            // =========================
            // JENIS KELAMIN
            // =========================
            $jenisKelamin = $this->normalisasiJenisKelamin(
                $this->value($row, 'jenis_kelamin')
            );

            if (
                $this->value($row, 'jenis_kelamin') &&
                !$jenisKelamin
            ) {
                $errors[] = "Baris {$baris}: jenis kelamin harus Laki-laki atau Perempuan.";
            }

            // =========================
            // DATA IMPORT
            // =========================
            $dataImport[] = [
                'siswa' => [
                    'nama' => $nama,
                    'nik' => $nik,
                    'nis' => $nis,
                    'nisn' => $nisn,

                    'tempat_lahir' => $this->value(
                        $row,
                        'tempat_lahir'
                    ),

                    'tanggal_lahir' => $this->formatTanggal(
                        $row['tanggal_lahir'] ?? null
                    ),

                    'jenis_kelamin' => $jenisKelamin,

                    'agama' => $this->value(
                        $row,
                        'agama'
                    ),

                    'status_anak' => $this->value(
                        $row,
                        'status_anak'
                    ),

                    'anak_ke' => $this->value(
                        $row,
                        'anak_ke'
                    ),

                    'alamat' => $this->value(
                        $row,
                        'alamat'
                    ),

                    'no_hp' => $this->value(
                        $row,
                        'no_hp'
                    ),

                    'asal_sekolah' => $this->value(
                        $row,
                        'asal_sekolah'
                    ),

                    'tanggal_diterima' => $this->formatTanggal(
                        $row['tanggal_diterima'] ?? null
                    ),
                ],

                'kelas' => $this->value(
                    $row,
                    'kelas'
                ),

                'orang_tua' => [
                    'nama_ayah' => $namaAyah,
                    'nama_ibu' => $namaIbu,

                    'alamat_orang_tua' => $this->value(
                        $row,
                        'alamat_orang_tua'
                    ),

                    'no_hp_orang_tua' => $this->value(
                        $row,
                        'no_hp_orang_tua'
                    ),

                    'pekerjaan_ayah' => $this->value(
                        $row,
                        'pekerjaan_ayah'
                    ),

                    'pekerjaan_ibu' => $this->value(
                        $row,
                        'pekerjaan_ibu'
                    ),
                ],

                'wali' => [
                    'nama_wali' => $namaWali,

                    'alamat_wali' => $this->value(
                        $row,
                        'alamat_wali'
                    ),

                    'no_hp_wali' => $this->value(
                        $row,
                        'no_hp_wali'
                    ),

                    'pekerjaan_wali' => $this->value(
                        $row,
                        'pekerjaan_wali'
                    ),
                ],
            ];
        }

        // =========================
        // JIKA ADA ERROR
        // =========================
        if (!empty($errors)) {
            throw ValidationException::withMessages([
                'file' => $errors,
            ]);
        }

        // =========================
        // CEK TAHUN AJARAN
        // =========================
        $adaDataDenganKelas = collect($dataImport)
            ->contains(function ($data) {
                return !empty($data['kelas']);
            });

        $tahunAjaranAktif = null;

        if ($adaDataDenganKelas) {
            $tahunAjaranAktif = TahunAjaran::where(
                'status_aktif',
                true
            )->first();

            if (!$tahunAjaranAktif) {
                throw ValidationException::withMessages([
                    'file' => [
                        'Belum ada tahun ajaran aktif. Aktifkan tahun ajaran terlebih dahulu sebelum melakukan import siswa yang memiliki kelas.',
                    ],
                ]);
            }
        }

        // =========================
        // PROSES IMPORT
        // =========================
        DB::transaction(function () use (
            $dataImport,
            $tahunAjaranAktif
        ) {
            foreach ($dataImport as $data) {

                // =========================
                // KELAS
                // =========================
                $kelasId = null;

                if ($data['kelas']) {
                    $kelas = Kelas::firstOrCreate(
                        [
                            'nama_kelas' => $data['kelas'],
                        ],
                        [
                            'wali_kelas' => null,
                        ]
                    );

                    $kelasId = $kelas->id;
                }

                $dataSiswa = array_merge(
                    $data['siswa'],
                    [
                        'kelas_id' => $kelasId,
                    ]
                );

                // =========================
                // CARI SISWA YANG SUDAH ADA
                // =========================
                $siswa = null;

                /*
                 * NIK menjadi identitas utama pertama
                 * yang digunakan untuk mencari siswa lama.
                 */
                if (!empty($dataSiswa['nik'])) {
                    $siswa = Siswa::where(
                        'nik',
                        $dataSiswa['nik']
                    )->first();
                }

                /*
                 * Kalau NIK belum ditemukan,
                 * coba cari melalui NISN.
                 */
                if (
                    !$siswa &&
                    !empty($dataSiswa['nisn'])
                ) {
                    $siswa = Siswa::where(
                        'nisn',
                        $dataSiswa['nisn']
                    )->first();
                }

                /*
                 * Terakhir cari melalui NIS.
                 */
                if (
                    !$siswa &&
                    !empty($dataSiswa['nis'])
                ) {
                    $siswa = Siswa::where(
                        'nis',
                        $dataSiswa['nis']
                    )->first();
                }

                // Apakah benar-benar siswa baru?
                $siswaBaru = !$siswa;

                // =========================
                // SIMPAN / UPDATE SISWA
                // =========================
                if ($siswa) {
                    $siswa->update($dataSiswa);
                } else {
                    $dataSiswa['status_siswa'] = 'Aktif';

                    $siswa = Siswa::create(
                        $dataSiswa
                    );
                }

                // =========================
                // ORANG TUA
                // =========================
                $adaOrangTua = collect(
                    $data['orang_tua']
                )->filter()->isNotEmpty();

                if ($adaOrangTua) {
                    OrangTua::updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                        ],
                        $data['orang_tua']
                    );
                }

                // =========================
                // WALI
                // =========================
                $adaWali = collect(
                    $data['wali']
                )->filter()->isNotEmpty();

                if ($adaWali) {
                    Wali::updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                        ],
                        $data['wali']
                    );
                }

                // =========================
                // RIWAYAT KELAS
                // =========================
                if (
                    $siswaBaru &&
                    $kelasId &&
                    $tahunAjaranAktif
                ) {
                    RiwayatKelas::updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                            'kelas_id' => $kelasId,
                            'tahun_ajaran_id' =>
                            $tahunAjaranAktif->id,
                        ],
                        [
                            'keterangan' => 'Siswa Baru',
                        ]
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
            return Date::excelToDateTimeObject(
                $value
            )->format('Y-m-d');
        }

        try {
            return Carbon::parse(
                $value
            )->format('Y-m-d');
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

        if (
            in_array(
                $value,
                [
                    'l',
                    'laki-laki',
                    'laki laki',
                    'pria',
                ]
            )
        ) {
            return 'Laki-laki';
        }

        if (
            in_array(
                $value,
                [
                    'p',
                    'perempuan',
                    'wanita',
                ]
            )
        ) {
            return 'Perempuan';
        }

        return null;
    }


    private function barisBerisiData($row)
    {
        foreach ($row as $value) {
            if (
                $value !== null &&
                $value !== ''
            ) {
                return true;
            }
        }

        return false;
    }
}
