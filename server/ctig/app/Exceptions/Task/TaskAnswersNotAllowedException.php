<?php

namespace App\Exceptions\Task;

use App\Exceptions\BaseException;
use Illuminate\Support\Facades\Log;


class TaskAnswersNotAllowedException extends BaseException
{
    public function __construct(
        public array $context,
        string $message = "Произошла ошибка при сохранении ответа"
    ){
        parent::__construct($message);
    }
    public function report(){
        Log::channel('single')->critical('UNEXPECTED: trying load answer to task, where not allowed answers', $this->context); 
    }
}
