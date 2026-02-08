<?php

namespace App\Http\Requests\Exam;

use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Dto\ExamDto;

class ExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'beginTime'=>
                    [
                        'required',
                        'date'
                    ],
            'addressId'=>
                    [
                        'required',
                        'integer', 
                        'min:0'
                    ], 
            'capacity' => [
                        'required',
                        'integer',
                        'min:0',
                    ],

            'examTypeId' => [
                        'required',
                        'integer',
                        'min:0',
                    ],

            'comment' => [
                        'nullable',
                        'string',
                    ],

            'testers' => [
                        'required',
                        'array',
                    ],

            'testers.*' => [
                        'required',
                        'integer',
                        'min:0',
                        'exists:users,id',
                    ],
        ];
    }

    public function attributes(){
        return [
            'beginTime' => 'время начала экзамена',
            'addressId' => 'идентификатор адреса',
            'capacity' => 'вместимость',
            'examTypeId' => 'идентификатор типа экзамена',
            'comment' => 'комментарий к экзамену',
            'testers' => 'тестеры',
            'testers.*' => 'тестеры'
        ];
    }

   public function getDto(): ExamDto{
        return new ExamDto(
            new DateTime($this->get('beginTime')),
            $this->get('addressId'),
            $this->get('capacity'),
            $this->get('examTypeId'),
            $this->get('comment'),
            $this->get('testers')
        );
   }
}
