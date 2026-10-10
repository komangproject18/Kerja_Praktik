<?php

namespace App\Exports;

use App\Models\RiwayatKelas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RiwayatKelasExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithColumnFormatting,
    WithStyles
{
    protected $tahunDari;
    protected $tahunSampai;
    protected $kelasId;
    protected $keterangan;
    protected $search;

    protected $nomor = 0;


    public function __construct(
        $tahunDari,
        $tahunSampai,
        $kelasId = null,
        $keterangan = null,
        $search = null
    ) {
        $this->tahunDari = $tahunDari;
        $this->tahunSampai = $tahunSampai;
        $this->kelasId = $kelasId;
        $this->keterangan = $keterangan;
        $this->search = $search;
    }


    public function collection()
    {
        return RiwayatKelas::with([
            'siswa',
            'kelas',
            'tahunAjaran',
        ])
            ->join(
                'tahun_ajaran',
                'riwayat_kelas.tahun_ajaran_id',
                '=',
                'tahun_ajaran.id'
            )
            ->select('riwayat_kelas.*')

            ->whereBetween(
                'tahun_ajaran.nama_tahun_ajaran',
                [
                    $this->tahunDari,
                    $this->tahunSampai,
                ]
            )

            ->when($this->kelasId, function ($query) {
                $query->where(
                    'riwayat_kelas.kelas_id',
                    $this->kelasId
                );
            })

            ->when($this->keterangan, function ($query) {
                $query->where(
                    'riwayat_kelas.keterangan',
                    $this->keterangan
                );
            })

            ->when($this->search, function ($query) {
                $search = $this->search;

                $query->whereHas(
                    'siswa',
                    function ($q) use ($search) {
                        $q->where(
                            'nama',
                            'like',
                            '%' . $search . '%'
                        )
                            ->orWhere(
                                'nik',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'nis',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'nisn',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                );
            })

            ->orderBy(
                'tahun_ajaran.nama_tahun_ajaran',
                'desc'
            )

            ->get();
    }


    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'NIK',
            'NIS',
            'NISN',
            'Jenis Kelamin',
            'Kelas',
            'Tahun Ajaran',
            'Keterangan',
            'Status Saat Ini',
        ];
    }


    public function map($riwayat): array
    {
        $this->nomor++;

        return [
            $this->nomor,
            $riwayat->siswa->nama ?? '-',
            $riwayat->siswa->nik ?? '-',
            $riwayat->siswa->nis ?? '-',
            $riwayat->siswa->nisn ?? '-',
            $riwayat->siswa->jenis_kelamin ?? '-',
            $riwayat->kelas->nama_kelas ?? '-',
            $riwayat->tahunAjaran
                ->nama_tahun_ajaran ?? '-',
            $riwayat->keterangan ?? '-',
            $riwayat->siswa->status_siswa ?? '-',
        ];
    }


    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
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
