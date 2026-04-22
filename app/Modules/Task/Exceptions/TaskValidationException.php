<?php

namespace App\Modules\Task\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class TaskValidationException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 422);
    }
}
