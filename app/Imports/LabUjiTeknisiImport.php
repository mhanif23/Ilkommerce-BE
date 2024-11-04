<?php

namespace App\Imports;

use App\Models\Profile\LabUjiTeknisi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LabUjiTeknisiImport implements ToModel, WithHeadingRow
{
    protected $lab_uji_id;

    public function __construct($lab_uji_id)
    {
        $this->lab_uji_id = $lab_uji_id;
    }

    public function model(array $row)
    {
        return new LabUjiTeknisi([
            'lab_uji_id' => $this->lab_uji_id,
            'nik' => $row['nik'],
            'nama_teknisi' => $row['nama_teknisi'],
            'nomor_sertifikat_teknisi' => $row['nomor_sertifikat_teknisi'],
            'lembaga_sertifikasi' => $row['lembaga_sertifikasi'],
            'email' => $row['email'],
            'telepon' => $row['telepon'],
            'file_sertifikat' => $row['file_sertifikat'],
        ]);
    }
}
