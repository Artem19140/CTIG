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
            'comment'=>'required|string', 
            'testers'=>['required', 'array'],
            'testers.*' => ['required', 'integer','min:0']
        ];
    }

    public function attributes(){
        return [
            'begin_time' => 'время начала экзамена',
            'exam_adress_id' => 'идентификатора адреса',
            'capacity' => 'вместимость',
            'exam_type_id' => 'идентификатора типа экзамена',
            'comment' => 'комментарий к экзамену',
        ];
    }

    public function messages(){
        return [
            'required' => "Поле :attribute должно быть заполненным",
            'string' => "Поле :attribute должно быть строкой",
            'date' =>  'Поле :attribute должно быть датой',
            'integer' => "Поле :attribute должно быть целым числом",
            'min'=>'Значение :attribute должно быть больше :min'
        ];
    }
}
