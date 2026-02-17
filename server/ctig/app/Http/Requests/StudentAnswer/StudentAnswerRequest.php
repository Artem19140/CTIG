<?php

namespace App\Http\Requests\StudentAnswer;

use Illuminate\Foundation\Http\FormRequest;

class StudentAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'examId' => 'required|integer|min:0|exists:exams,id', 
            'blockId' => 'required|integer|min:0|exists:blocks,id', 
            'taskId' => 'required|integer|min:0|exists:tasks,id', 
            'studentAnswer' => 'nullamble|string', //вот хз прям, он же может и не ответить и это мб не строка будеты
        ];
    }
}
