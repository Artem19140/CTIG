<?php

namespace App\Exceptions\Attempt;

use App\Exceptions\BaseException;
use Illuminate\Http\Request;
use Log;

class AnswerManualCheckException extends BaseException
{
    public function __construct(
        public array $context,
        string $message = "Произошла ошибка при оценке ответа"
    ){
        parent::__construct($message);
    }

    public function report(Request $request){
        Log::warning('Manual attempt checking', [
            'attempt_answer_id' => $request->route('attemptAnswer')?->id,
            ...$this->context
        ]);

       
    }

    public function render(Request $request){
        if ($request->expectsJson()) {
            return response()->json([
                'errors' => [
                    'mark' => $this->message
                ]
            ], 422);
        }

        return back()->withErrors([
            'mark' => $this->message
        ]);

    }
}
