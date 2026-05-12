<?php

namespace App\Exceptions\Attempt;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AttemptAnswerValidationException extends Exception
{
    public function __construct(
        public array $context,
        string $message = "Invalid attempt answer format"
    ){
        parent::__construct($message);
    }
    public function report(){
        Log::channel('single')->critical('UNEXPECTED: attempt answer validation failed', $this->context);
    }

    public function render(Request $request){

        if($request->wantsJson()){
            return response()->json([
                'message' => 'Произошла ошибка при обработке ответа'
            ], 400);
        }

        return Inertia::flash(['error' => 'Произошла ошибка при обработке ответа'])->back();
    }
}
