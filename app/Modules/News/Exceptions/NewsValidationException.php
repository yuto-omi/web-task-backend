<?php

namespace App\Modules\News\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class NewsValidationException extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 422);
    }
}
