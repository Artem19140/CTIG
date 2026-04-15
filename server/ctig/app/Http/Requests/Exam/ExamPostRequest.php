<?php

namespace App\Http\Requests\Exam;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Dto\ExamDto;

class ExamPostRequest extends FormRequest
{
    public function authorize(User $user): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'time'=>
                    [
                        'required',
                        'date_format:H:i',
                        //Rule::date()->afterOrEqual(Carbon::now($this->user()->time_zone))
                    ],
            'date'=>
                    [
                        'required',
                        'date',
                        Rule::date()->afterOrEqual(Carbon::now($this->user()->time_zone))
                    ],
            'addressId'=>
                    [
                        'required',
                        'integer', 
                        'min:1',
                        'exists:addresses,id'
                    ], 

            'examTypeId' => [
                        'required',
                        'integer',
                        'min:1',
                        'exists:exam_types,id'
                    ],

            'comment' => [
                        'nullable',
                        'string',
                        'max:256'
                    ],
            'capacity' => [
                        'required',
                        'integer',
                        'min:1'
                    ],

            'examiners' => [
                        'required',
                        'array',
                    ],

            'examiners.*' => [
                        'required',
                        'integer',
                        'min:1',
                        'exists:users,id',
                    ],
        ];
    }

        /**
     * Получить сообщения об ошибках для определенных правил валидации.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
                'date.after_or_equal' => 'Нельзя создать экзамен на прошедшую дату.',
                'time.date_format' => 'Время должно быть в формате чч:мм'
            ];
    }

   public function getDto(): ExamDto{
        return new ExamDto(
            Carbon::createFromFormat('Y-m-d H:i',$this->input('date'). ' ' . $this->input('time'), request()->user()->center->time_zone),
            \intval($this->input('addressId')),
            \intval($this->input('examTypeId')),
            \strval($this->input('comment')),
            $this->input('examiners'),
            \intval($this->input('capacity')),
            
        );
   }

}
