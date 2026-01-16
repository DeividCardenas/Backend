<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BusinessLogicException extends Exception
{
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'error' => 'Business Logic Error',
            'message' => $this->getMessage(),
        ], 400);
    }
}
