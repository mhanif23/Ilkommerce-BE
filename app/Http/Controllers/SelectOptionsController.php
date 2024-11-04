<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Product;
use App\Models\Mobile\DetailInspectProduct;
use App\Models\QuartalReport\QuartalReport;
use App\Models\Masterdata\{Negara, Provinsi, Kota, Kecamatan, Kelurahan, ComplianceCase};
use App\Models\Profile\{BadanUsaha, LSPro, LabUji};

class SelectOptionsController extends Controller
{
    public function provinsi(Request $request)
    {
        $data = Provinsi::orderBy('kode_provinsi', 'asc')->get();
        return $this->api_response_success('OK', $data->toArray());
    }

    public function kota(Request $request)
    {
        $data = Kota::where('kode_provinsi', $request->kode_provinsi)->orderBy('kode_kota', 'asc')->get();
        return $this->api_response_success('OK', $data->toArray());
    }

    public function kecamatan(Request $request)
    {
        $data = Kecamatan::where('kode_kota', $request->kode_kota)->orderBy('kode_kecamatan', 'asc')->get();
        return $this->api_response_success('OK', $data->toArray());
    }

    public function kelurahan(Request $request)
    {
        $data = Kelurahan::where('kode_kecamatan', $request->kode_kecamatan)->orderBy('kode_kelurahan', 'asc')->get();
        return $this->api_response_success('OK', $data->toArray());
    }

    public function products(Request $request)
    {
        $query = Product::with('toko')->get();

        $data = $query->orderBy('id', 'asc')->get();

        return $this->api_response_success('OK', $data->toArray());
    }
    
    public function categories(Request $request)
    {
        $query = Category::get();

        $data = $query->orderBy('id', 'asc')->get();

        return $this->api_response_success('OK', $data->toArray());
    }

}
