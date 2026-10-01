<?php

namespace App\Exports;

use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $kelasId;
    protected $nomor = 0;

    public function __construct($kelasId = null)
    {
        $this->kelasId = $kelasId;
    }

    public function collection()
    {
        return Siswa::with(['kelas', 'orangTua', 'wali'])
            ->when($this->kelasId, function ($query) {
                $query->where('kelas_id', $this->kelasId);
            })
            ->orderBy('nama', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'NIS',
            'NISN',
            'TTL',
            'JK',
            'Agama',
            'Status Anak',
            'Anak Ke',
            'Alamat Siswa',
            'No HP Siswa',
            'Asal Sekolah',
            'Diterima di SMK Kelas',
            'Tanggal Diterima',
            'Nama Ayah',
            'Nama Ibu',
            'Alamat Orang Tua',
            'No HP Orang Tua',
            'Pekerjaan Ayah',
            'Pekerjaan Ibu',
            'Nama Wali',
            'Alamat Wali',
            'No HP Wali',
            'Pekerjaan Wali',
        ];
    }

    public function map($siswa): array
    {
        $this->nomor++;

        $ttl = '-';

        if ($siswa->tempat_lahir || $siswa->tanggal_lahir) {
            $tanggalLahir = $siswa->tanggal_lahir
                ? date('d-m-Y', strtotime($siswa->tanggal_lahir))
                : '-';

            $ttl = ($siswa->tempat_lahir ?? '-') . ', ' . $tanggalLahir;
        }

        return [
            $this->nomor,
            $siswa->nama,
            $siswa->nis ?? '-',
            $siswa->nisn ?? '-',
            $ttl,
            $siswa->jenis_kelamin ?? '-',
            $siswa->agama ?? '-',
            $siswa->status_anak ?? '-',
            $siswa->anak_ke ?? '-',
            $siswa->alamat ?? '-',
            $siswa->no_hp ?? '-',
            $siswa->asal_sekolah ?? '-',
            $siswa->kelas->nama_kelas ?? '-',
            $siswa->tanggal_diterima ? date('d-m-Y', strtotime($siswa->tanggal_diterima)) : '-',

            $siswa->orangTua->nama_ayah ?? '-',
            $siswa->orangTua->nama_ibu ?? '-',
            $siswa->orangTua->alamat_orang_tua ?? '-',
            $siswa->orangTua->no_hp_orang_tua ?? '-',
            $siswa->orangTua->pekerjaan_ayah ?? '-',
            $siswa->orangTua->pekerjaan_ibu ?? '-',

            $siswa->wali->nama_wali ?? '-',
            $siswa->wali->alamat_wali ?? '-',
            $siswa->wali->no_hp_wali ?? '-',
            $siswa->wali->pekerjaan_wali ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }
}