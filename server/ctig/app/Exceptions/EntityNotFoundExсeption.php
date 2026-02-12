<?php

namespace App\Exceptions;
use Illuminate\Http\JsonResponse;
use Exception;

class EntityNotFoundExсeption extends Exception
{
    public function __construct(string $entity = ""){
        parent::__construct("{$entity} не существует",422);
    }

     public function render($request): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], $this->code);
    }
}
