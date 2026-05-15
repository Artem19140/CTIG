<?php

namespace App\Exceptions;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BaseException extends Exception
{
    public function __construct(string $message = ""){
        parent::__construct($message);
    }

    public function render(Request $request){

        if($request->expectsJson()){
            return response()->json([
                'message' => $this->getMessage()
            ], 400);
        }

        return Inertia::flash(['error' => $this->getMessage()])->back();
    }
}
