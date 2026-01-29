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
            'exam_address_id'=>'required|integer|min:0|exists:exam_addresses,id', 
            'capacity'=>'required|integer|min:0',
            'exam_type_id'=>'required|integer|min:0|exists:exam_types,id',
            'comment'=>'sometimes|string', 
            'testers'=>'required|array',
            'testers.*' => 'required|integer|min:0|exists:users,id'
        ];
    }

    public function attributes(){
        return [
            'begin_time' => 'время начала экзамена',
            'exam_address_id' => 'идентификатор адреса',
            'capacity' => 'вместимость',
            'exam_type_id' => 'идентификатор типа экзамена',
            'comment' => 'комментарий к экзамену',
            'testers' => 'тестеры',
            'testers.*' => 'тестеры'
        ];
    }

   
}
