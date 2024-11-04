<?php

namespace App\Http\Controllers\Api\Review;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::where('user_id', Auth::id())->get();
        return $this->api_response_success('Data Review berhasil diambil.', $reviews->toArray());
    }

    public function submit(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->api_response_validator('Periksa data yang anda isi!', [], $validator->errors()->toArray());
        }

        try {
            DB::beginTransaction();

            $review = Review::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'order_id' => $request->order_id,
                'rating' => $request->rating,
                'review' => $request->review,
            ]);

            $message = "Review berhasil disimpan";
            
            DB::commit();
            return $this->api_response_success($message, $review->toArray());
        } catch (\Exception $e) {
            DB::rollback();
            return $this->api_response_error('Gagal menyimpan Review.', ['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $review = Review::where('user_id', Auth::id())->find($id);

        if (!$review) {
            return $this->api_response_error('Review tidak ditemukan.', [], [], 404);
        }

        return $this->api_response_success('Data Review berhasil diambil.', $review);
    }

    public function update(Request $request, $id)
    {
        $review = Review::where('user_id', Auth::id())->find($id);

        if (!$review) {
            return $this->api_response_error('Review tidak ditemukan.', [], [], 404);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|required|integer|between:1,5',
            'review' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->api_response_validator('Periksa data yang anda isi!', [], $validator->errors()->toArray());
        }

        try {
            DB::beginTransaction();

            if ($request->has('rating')) {
                $review->rating = $request->rating;
            }
            if ($request->has('review')) {
                $review->review = $request->review;
            }

            $review->save();

            DB::commit();
            return $this->api_response_success('Review berhasil diperbarui.', $review->toArray());
        } catch (\Exception $e) {
            DB::rollback();
            return $this->api_response_error('Gagal memperbarui Review.', ['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $review = Review::where('user_id', Auth::id())->find($id);

        if (!$review) {
            return $this->api_response_error('Review tidak ditemukan.', [], [], 404);
        }

        try {
            DB::beginTransaction();

            $review->delete();

            DB::commit();
            return $this->api_response_success('Review berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->api_response_error('Gagal menghapus Review.', ['error' => $e->getMessage()]);
        }
    }
}
