<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('product')->get();
        return $this->api_response_success('Data Order berhasil diambil.', $orders->toArray());
    }

    public function submit(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->api_response_validator('Periksa data yang anda isi!', [], $validator->errors()->toArray());
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'total_price' => $request->total_price,
                'status' => 'pending',
                'created_by' => Auth::id(),
            ]);

            $message = "Order berhasil dibuat";
            
            DB::commit();
            return $this->api_response_success($message, $order->toArray());
        } catch (\Exception $e) {
            DB::rollback();
            return $this->api_response_error('Gagal membuat Order.', ['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())->find($id);

        if (!$order) {
            return $this->api_response_error('Order tidak ditemukan.', [], [], 404);
        }

        return $this->api_response_success('Data Order berhasil diambil.', $order->toArray());
    }

    public function update(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->find($id);

        if (!$order) {
            return $this->api_response_error('Order tidak ditemukan.', [], [], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|required|in:pending,deliver,completed,canceled,reviewed',
        ]);

        if ($validator->fails()) {
            return $this->api_response_validator('Periksa data yang anda isi!', [], $validator->errors()->toArray());
        }

        try {
            DB::beginTransaction();

            if ($request->has('status')) {
                $order->status = $request->status;
            }

            $order->updated_by = Auth::id();
            $order->save();

            DB::commit();
            return $this->api_response_success('Status Order berhasil diperbarui.', $order->toArray());
        } catch (\Exception $e) {
            DB::rollback();
            return $this->api_response_error('Gagal memperbarui Order.', ['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $order = Order::where('user_id', Auth::id())->find($id);

        if (!$order) {
            return $this->api_response_error('Order tidak ditemukan.', [], [], 404);
        }

        try {
            DB::beginTransaction();

            $order->delete();

            DB::commit();
            return $this->api_response_success('Order berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->api_response_error('Gagal menghapus Order.', ['error' => $e->getMessage()]);
        }
    }

    public function checkoutCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.total_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->api_response_validator('Periksa data yang anda isi!', [], $validator->errors()->toArray());
        }

        try {
            DB::beginTransaction();

            foreach ($request->products as $item) {
                Order::create([
                    'user_id' => Auth::id(),
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['total_price'],
                    'status' => 'pending',
                    'created_by' => Auth::id(),
                ]);
            }

            DB::commit();
            return $this->api_response_success("Checkout berhasil.", $request->products);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->api_response_error('Gagal melakukan checkout.', ['error' => $e->getMessage()]);
        }
    }

}
