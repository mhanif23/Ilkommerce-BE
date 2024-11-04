<?php

namespace App\Imports;

use App\Models\Profile\LabUjiAkreditasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LabUjiAkreditasiImport implements ToModel, WithHeadingRow
{
    protected $lab_uji_id;

    public function __construct($lab_uji_id)
    {
        $this->lab_uji_id = $lab_uji_id;
    }

    public function model(array $row)
    {
        return new LabUjiAkreditasi([
            'lab_uji_id' => $this->lab_uji_id,
            'kategori_produk' => $row['kategori_produk'],
            'sub_kategori_produk' => $row['sub_kategori_produk'],
            'nama_produk' => $row['nama_produk'],
            'no_sni' => $row['no_sni'],
            'judul_sni' => $row['judul_sni'],
            'skema_sertifikasi' => $row['skema_sertifikasi'],
            'created_by' => auth()->user()->id,
        ]);
    }
}
