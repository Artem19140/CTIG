<?php

namespace App\Http\Requests\Exam;

use App\Models\User;
use DateTime;
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
                        'max:256'
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

   public function getDto(): ExamDto{
        return new ExamDto(
            new DateTime($this->get('beginTime')),
            \intval($this->get('addressId')),
            \intval($this->get('capacity')),
            \intval($this->get('examTypeId')),
            \strval($this->get('comment')),
            $this->get('testers')
        );
   }
}
