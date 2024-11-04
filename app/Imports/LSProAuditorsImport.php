<?php

namespace App\Imports;

use App\Models\Profile\LSProAuditor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LSProAuditorsImport implements ToModel, WithHeadingRow
{
    protected $lspro_id;

    public function __construct($lspro_id)
    {
        $this->lspro_id = $lspro_id;
    }

    public function model(array $row)
    {
        return new LSProAuditor([
            'lspro_id' => $this->lspro_id,
            'nik' => $row['nik'],
            'nama_auditor' => $row['nama_auditor'],
            'nomor_sertifikat_auditor' => $row['nomor_sertifikat_auditor'],
            'lembaga_sertifikasi' => $row['lembaga_sertifikasi'],
            'email' => $row['email'],
            'telepon' => $row['telepon'],
            'file_auditor' => $row['file_auditor'],
        ]);
    }
}
