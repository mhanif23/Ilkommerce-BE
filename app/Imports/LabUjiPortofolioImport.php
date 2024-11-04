<?php

namespace App\Imports;

use App\Models\Profile\LabUjiPortofolio;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LabUjiPortofolioImport implements ToModel, WithHeadingRow
{
    protected $lab_uji_id;

    public function __construct($lab_uji_id)
    {
        $this->lab_uji_id = $lab_uji_id;
    }

    public function model(array $row)
    {
        return new LabUjiPortofolio([
            'lab_uji_id' => $this->lab_uji_id,
            'tanggal_kegiatan' => $row['tanggal_kegiatan'],
            'nama_kegiatan' => $row['nama_kegiatan'],
            'lembaga_terkait' => $row['lembaga_terkait'],
            'nama_produk' => $row['nama_produk'],
            'deskripsi_kegiatan' => $row['deskripsi_kegiatan'],
            'file' => $row['file'],
            'created_by' => auth()->user()->id,
        ]);
    }
}
