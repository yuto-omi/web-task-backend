<?php

namespace App\Modules\Project\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ProjectValidationException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 422);
    }
}
