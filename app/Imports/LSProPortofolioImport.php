<?php

namespace App\Imports;

use App\Models\Profile\LSProPortofolio;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LSProPortofolioImport implements ToModel, WithHeadingRow
{
    protected $lspro_id;

    public function __construct($lspro_id)
    {
        $this->lspro_id = $lspro_id;
    }

    public function model(array $row)
    {
        return new LSProPortofolio([
            'lspro_id' => $this->lspro_id,
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
