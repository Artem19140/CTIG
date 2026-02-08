<?php

namespace App\Exceptions;
use Exception;
use Illuminate\Http\JsonResponse;

class BusinessException extends Exception
{
    public function __construct(string $message = "", int $code = 422){
        parent::__construct($message,$code);
    }

     public function render($request): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], $this->code);
    }
}
