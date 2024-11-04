<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * return api response success
     * 
     * @param Array $data
     * @param String $message
     * @param Int $code
     * @param Array $additional_data
     * 
     * @return JsonResponse
     */
    protected function api_response_success(String $message, Array $data = [], Array $additional_data = [], Int $response_code = 200): JsonResponse {
        return response()->json(
            array_merge([
                'metadata' => [
                    'code' => $response_code,
                    'status' => 'success',
                    'message' => $message,
                    'errors' => [],
                ],
                'response' => $data,
            ], $additional_data), $response_code
        );
    }


    /**
     * return api response error
     * 
     * @param Array $data
     * @param String $message
     * @param Int $code
     * @param Array $errors
     * 
     * @return JsonResponse
     */
    protected function api_response_error(String $message, Array $data = [], Array $errors = [], Int $response_code = 500): JsonResponse {
        return response()->json([
            'metadata' => [
                'code' => $response_code,
                'status' => 'error',
                'message' => $message,
                'errors' => $errors,
            ],
            'response' => $data,
        ], $response_code);
    }


    /**
     * return api response error
     * 
     * @param Array $data
     * @param String $message
     * @param Int $code
     * @param Array $errors
     * 
     * @return JsonResponse
     */
    protected function api_response_validator(String $message, Array $data = [], Array $errors = [], Int $response_code = 400): JsonResponse {
        return response()->json([
            'metadata' => [
                'code' => $response_code,
                'status' => 'error',
                'message' => $message,
                'errors' => $errors,
            ],
            'response' => $data,
        ], $response_code);
    }

    /**
     * Store file with specific folder structure
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param String $folder
     * 
     * @return String
     */
    protected function storeFile($file, $folder)
    {
        $userId = Auth::id();
        $date = now();
        $filePath = $file->storeAs(
            "{$folder}/{$date->year}/{$date->month}/{$date->day}/{$userId}",
            $file->hashName(),
            'public'
        );
        return $filePath;
    }
}
