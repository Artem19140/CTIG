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
            'exam_id' => 'required|integer|min:0', 
            'exam_block_id' => 'required|integer|min:0', 
            'task_id' => 'required|integer|min:0', 
            //'student_answer' => 'sometimes|string', //вот хз прям, он же может и не ответить и это мб не строка будеты
        ];
    }

    public function attributes(){
        return [
            'exam_block_id' => 'идентификатор блока экзамена',
            'exam_id' => 'идентификатор экзамена',
            'task_id' => 'идентификатор задания',
            'student_answer' => 'ответ студента'
        ];
    }
}
