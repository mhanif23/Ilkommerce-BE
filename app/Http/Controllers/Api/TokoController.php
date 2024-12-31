<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Toko;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TokoController extends Controller
{
    public function index()
    {
        $toko = Toko::all();
        return $this->api_response_success('Data Toko berhasil diambil.', $toko->toArray());
    }

    public function submit(Request $request)
    {
        $user = Auth::user();

        if ($user->toko_id) {
            return $this->api_response_error('Anda sudah memiliki profil Toko.', [], [], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'nama_toko' => 'required|unique:toko,nama_toko',
            'jenis_toko' => 'required',
            'alamat_lengkap' => 'required',
            'kode_provinsi' => 'required|exists:masterdata_provinsi,kode_provinsi',
            'kode_kota' => 'required|exists:masterdata_kota,kode_kota',
            'kode_kecamatan' => 'required|exists:masterdata_kecamatan,kode_kecamatan',
            'kode_kelurahan' => 'required|exists:masterdata_kelurahan,kode_kelurahan',
            'kode_pos' => 'required|string',
            'npwp' => 'nullable|unique:toko,npwp',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'email_toko' => 'nullable|email|unique:toko,email_toko',
            'telephone_toko' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->api_response_validator('Periksa data yang anda isi!', [], $validator->errors()->toArray());
        }

        try {
            DB::beginTransaction();

            $toko = Toko::create([
                'nama_toko' => $request->nama_toko,
                'jenis_toko' => $request->jenis_toko,
                'alamat_lengkap' => $request->alamat_lengkap,
                'kode_provinsi' => $request->kode_provinsi,
                'kode_kota' => $request->kode_kota,
                'kode_kecamatan' => $request->kode_kecamatan,
                'kode_kelurahan' => $request->kode_kelurahan,
                'kode_pos' => $request->kode_pos,
                'npwp' => $request->npwp,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'email_toko' => $request->email_toko,
                'telephone_toko' => $request->telephone_toko,
                'created_by' => Auth::id(),
            ]);

            $user->toko_id = $toko->id;
            $user->save();

            $message = "Data Toko Berhasil Disimpan";
            
            DB::commit();
            return $this->api_response_success($message, $toko->toArray());
        } catch (\Exception $e) {
            DB::rollback();
            return $this->api_response_error('Gagal menyimpan profil Toko.', ['error' => $e->getMessage()]);
        }
    }

    public function show()
    {
        $user = Auth::user();
        $id = $user->toko_id;

        $toko = Toko::find($id);

        if (!$toko) {
            return $this->api_response_error('Toko tidak ditemukan.', [], [], 404);
        }

        return $this->api_response_success('Data Toko berhasil diambil.', $toko->toArray());
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $id = $user->toko_id;

        $toko = Toko::find($id);

        if (!$toko) {
            return $this->api_response_error('Toko tidak ditemukan.', [], [], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_toko' => 'sometimes|required|unique:toko,nama_toko,' . $id,
            'jenis_toko' => 'sometimes|required',
            'alamat_lengkap' => 'sometimes|required',
            'kode_provinsi' => 'sometimes|required|exists:masterdata_provinsi,kode_provinsi',
            'kode_kota' => 'sometimes|required|exists:masterdata_kota,kode_kota',
            'kode_kecamatan' => 'sometimes|required|exists:masterdata_kecamatan,kode_kecamatan',
            'kode_pos' => 'sometimes|required|string',
            'npwp' => 'nullable|unique:toko,npwp,' . $id,
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'email_toko' => 'nullable|email|unique:toko,email_toko,' . $id,
            'telephone_toko' => 'nullable|string',
            'kode_kelurahan' => 'sometimes|required|exists:masterdata_kelurahan,kode_kelurahan',
        ]);

        if ($validator->fails()) {
            return $this->api_response_validator('Periksa data yang anda isi!', [], $validator->errors()->toArray());
        }

        try {
            DB::beginTransaction();

            $toko->update($request->all());
            $toko->updated_by = Auth::id();
            $toko->save();

            DB::commit();
            return $this->api_response_success('Profil Toko berhasil diperbarui.', $toko->toArray());
        } catch (\Exception $e) {
            DB::rollback();
            return $this->api_response_error('Gagal memperbarui profil Toko.', ['error' => $e->getMessage()]);
        }
    }

    public function destroy()
    {
        $user = Auth::user();
        $id = $user->toko_id;

        $toko = Toko::find($id);

        if (!$toko) {
            return $this->api_response_error('Toko tidak ditemukan.', [], [], 404);
        }

        try {
            DB::beginTransaction();

            $toko->delete();

            DB::commit();
            return $this->api_response_success('Profil Toko berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->api_response_error('Gagal menghapus profil Toko.', ['error' => $e->getMessage()]);
        }
    }
}
