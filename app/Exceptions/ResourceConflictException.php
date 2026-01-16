<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ResourceConflictException extends Exception
{
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'error' => 'Resource Conflict',
            'message' => $this->getMessage(),
        ], 409);
    }
}
