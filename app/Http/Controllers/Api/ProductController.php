<?php

namespace App\Http\Controllers\Api\Product;

use Illuminate\Http\Request;
use Carbon\Carbon;      

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Product;

class ProductController extends Controller
{
    // Method untuk mendapatkan semua data produk 
    public function index(Request $request)
    {
        try {
            $products = Product::get();

            return datatables()->of($products)
                ->addIndexColumn()
                ->editColumn('foto_product', function($item) {
                    if($item->foto_product) {
                        return env('APP_URL') . '/storage/' . $item->foto_product;
                    } else {
                        return null;
                    }
                })
                ->addColumn('encrypt_id', function ($row) {
                    return Crypt::encrypt($row->id);
                })
                ->editColumn('created_at', function ($item) {
                    return \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y H:i:s');;
                })
                ->toJson();

        } catch (\Exception $e) {
            return $this->api_response_error('Failed to retrieve products.', [$e->getMessage()]);
        }
    }

    // Method untuk menyimpan data produk 
    public function store(Request $request)
    {
        $rules = [
            'toko_id' => 'required|exists:toko,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'foto_product' => 'required|file|mimes:jpeg,jpg,png',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->api_response_validator('Periksa data yang anda isi!', [], $validator->errors()->toArray());
        }

        $validatedData = $request->all();
        $validatedData['toko_id'] = Auth::user()->toko_id;

        if ($request->hasFile('foto_product')) {
            $validatedData['foto_product'] = $this->storeFile($request->file('foto_product'), 'products/foto_product');
        }

        $product = Product::create($validatedData);
        return $this->api_response_success('Product created successfully', $product->toArray(), []);
    }

    // Method untuk mendapatkan data spesifik produk berdasarkan ID 
    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $product = Product::find($id);
        if (!$product) {
            return $this->api_response_error('Product not found', [], [], 404);
        }
        return $this->api_response_success('Product retrieved successfully', $product->toArray());
    }

    // Method untuk memperbarui data produk.
    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $product = Product::find($id);

        if (!$product) {
            return $this->api_response_error('Product not found', [], [], 404);
        }

        $validatedData = $request->validate([
            'toko_id' => 'sometimes|required|exists:toko,id',
            'category_id' => 'sometimes|required|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:255',
            'model' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|integer',
            'foto_product' => 'sometimes|required|file|mimes:jpeg,jpg,png',
        ]);

        if ($request->hasFile('foto_product')) {
            $validatedData['foto_product'] = $this->storeFile($request->file('foto_product'), 'products/foto_product');
        }

        $product->update($validatedData);

        return $this->api_response_success('Product updated successfully', $product->toArray());
    }

    // Method untuk menghapus data produk
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $product = Product::find($id);

        if (!$product) {
            return $this->api_response_error('Product not found', [], [], 404);
        }

        $product->delete();

        return $this->api_response_success('Product deleted successfully', []);
    }
    
}

