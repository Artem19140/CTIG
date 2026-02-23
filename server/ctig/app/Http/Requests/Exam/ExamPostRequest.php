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
        return $user->isAdmin();
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
