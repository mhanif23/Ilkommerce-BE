<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->get();
        return $this->api_response_success('Data Cart berhasil diambil.', $cart->toArray());
    }

    public function submit(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->api_response_validator('Periksa data yang anda isi!', [], $validator->errors()->toArray());
        }

        try {
            DB::beginTransaction();

            // Check if product already in cart
            $cart = Cart::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($cart) {
                // Update quantity if product already in cart
                $cart->quantity += $request->quantity;
                $cart->updated_by = Auth::id();
                $cart->save();
            } else {
                // Create new cart item if not already in cart
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'created_by' => Auth::id(),
                ]);
            }

            $message = "Data Cart Berhasil Disimpan";
            
            DB::commit();
            return $this->api_response_success($message, $cart->toArray());
        } catch (\Exception $e) {
            DB::rollback();
            return $this->api_response_error('Gagal menyimpan Cart.', ['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $cart = Cart::where('user_id', Auth::id())->find($id);

        if (!$cart) {
            return $this->api_response_error('Cart tidak ditemukan.', [], [], 404);
        }

        return $this->api_response_success('Data Cart berhasil diambil.', $cart);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('user_id', Auth::id())->find($id);

        if (!$cart) {
            return $this->api_response_error('Cart tidak ditemukan.', [], [], 404);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'sometimes|required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->api_response_validator('Periksa data yang anda isi!', [], $validator->errors()->toArray());
        }

        try {
            DB::beginTransaction();

            if ($request->has('quantity')) {
                $cart->quantity = $request->quantity;
            }

            $cart->updated_by = Auth::id();
            $cart->save();

            DB::commit();
            return $this->api_response_success('Data Cart berhasil diperbarui.', $cart->toArray());
        } catch (\Exception $e) {
            DB::rollback();
            return $this->api_response_error('Gagal memperbarui Cart.', ['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $cart = Cart::where('user_id', Auth::id())->find($id);

        if (!$cart) {
            return $this->api_response_error('Cart tidak ditemukan.', [], [], 404);
        }

        try {
            DB::beginTransaction();

            $cart->delete();

            DB::commit();
            return $this->api_response_success('Cart berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->api_response_error('Gagal menghapus Cart.', ['error' => $e->getMessage()]);
        }
    }
}
