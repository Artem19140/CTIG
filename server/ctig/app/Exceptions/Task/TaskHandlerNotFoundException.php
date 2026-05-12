<?php

namespace App\Exceptions\Task;

use App\Exceptions\BaseException;
use Illuminate\Support\Facades\Log;

class TaskHandlerNotFoundException extends BaseException
{
    public function __construct(
        public array $context,
        string $message = "Произошла ошибка при сохранении ответа"
    ){
        parent::__construct($message);
    }
    public function report(){
        Log::channel('single')->critical('UNEXPECTED: handler for task type not found', $this->context); 
    }

}
