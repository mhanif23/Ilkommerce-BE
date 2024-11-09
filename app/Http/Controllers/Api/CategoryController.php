<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return $this->api_response_success('Data Category berhasil diambil.', $category->toArray());
    }

    public function submit(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->api_response_validator('Periksa data yang anda isi!', [], $validator->errors()->toArray());
        }

        try {
            DB::beginTransaction();

            $category = Category::create([
                'name' => $request->name,
            ]);

            $message = "Data Category Berhasil Disimpan";
            
            DB::commit();
            return $this->api_response_success($message, $category->toArray());
        } catch (\Exception $e) {
            DB::rollback();
            return $this->api_response_error('Gagal menyimpan Category.', ['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->api_response_error('Category tidak ditemukan.', [], [], 404);
        }

        return $this->api_response_success('Data Category berhasil diambil.', $category->toArray());
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->api_response_error('Category tidak ditemukan.', [], [], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required',
        ]);

        if ($validator->fails()) {
            return $this->api_response_validator('Periksa data yang anda isi!', [], $validator->errors()->toArray());
        }

        try {
            DB::beginTransaction();

            $category->update($request->all());
            $category->updated_by = Auth::id();
            $category->save();

            DB::commit();
            return $this->api_response_success('Data Category berhasil diperbarui.', $category->toArray());
        } catch (\Exception $e) {
            DB::rollback();
            return $this->api_response_error('Gagal memperbarui Category.', ['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->api_response_error('Category tidak ditemukan.', [], [], 404);
        }

        try {
            DB::beginTransaction();

            $category->delete();

            DB::commit();
            return $this->api_response_success('Category berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->api_response_error('Gagal menghapus profil Category.', ['error' => $e->getMessage()]);
        }
    }
}
