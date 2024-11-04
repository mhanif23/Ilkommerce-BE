<?php

namespace App\Imports;

use App\Models\Profile\LSProAkreditasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LSProAkreditasiImport implements ToModel, WithHeadingRow
{
    protected $lspro_id;

    public function __construct($lspro_id)
    {
        $this->lspro_id = $lspro_id;
    }

    public function model(array $row)
    {
        return new LSProAkreditasi([
            'lspro_id' => $this->lspro_id,
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
