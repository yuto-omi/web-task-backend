<?php

namespace App\Shared\Http;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function data(array $data, int $status = 200): JsonResponse
    {
        return response()->json($data, $status);
    }

    public static function message(string $message, int $status = 200): JsonResponse
    {
        return response()->json(['message' => $message], $status);
    }
}
