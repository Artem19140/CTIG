<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class ExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'begin_time'=>'required|date',
            'exam_adress_id'=>'required|integer|min:0', 
            'capacity'=>'required|integer|min:0',
            'exam_type_id'=>'required|integer|min:0',
            'comment'=>'sometimes|string', 
            'testers'=>'required|array',
            'testers.*' => 'required|integer|min:0'
        ];
    }

    public function attributes(){
        return [
            'begin_time' => 'время начала экзамена',
            'exam_adress_id' => 'идентификатор адреса',
            'capacity' => 'вместимость',
            'exam_type_id' => 'идентификатор типа экзамена',
            'comment' => 'комментарий к экзамену',
            'testers' => 'тестеры',
            'testers.*' => 'тестеры'
        ];
    }

   
}
