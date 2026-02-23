<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ForbiddenException extends Exception
{
    public function __construct(string $message = "Не найдено"){
        parent::__construct($message, 403);
    }
    public function render($request): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], $this->code);
    }
}
