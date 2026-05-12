<?php

namespace App\Exceptions\Attempt;

use App\Exceptions\BaseException;
use Illuminate\Support\Facades\Log;

class AttemptAnswerValidationException extends BaseException
{
    public function __construct(
        public array $context,
        string $message = "Произошла ошибка при обработке ответа"
    ){
        parent::__construct($message);
    }
    public function report(){
        Log::channel('single')->critical('UNEXPECTED: attempt answer validation failed', $this->context);
    }
}
