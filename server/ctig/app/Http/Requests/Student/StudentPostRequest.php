<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StudentPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'noPatronymic' =>
                [
                    'boolean'
                ],
            'surname' =>
                [
                    'required',
                    'string'
                ],
            'name' =>
                [
                    'required',
                    'string'
                ],
            'patronymic' =>
                [
                    'prohibited_if_accepted:noPatronymic',
                    'required_if_declined:noPatronymic',
                    'nullable',
                    'string'
                ],
            'patronymicLatin' =>
                [
                    'prohibited_if_accepted:noPatronymic',
                    'required_if_declined:noPatronymic',
                    'nullable',
                    'string'
                ],
            'dateBirth' =>
                [
                    'required',
                    'date'
                ],
            'surnameLatin' =>
                [
                    'required',
                    'string'
                ],
            'nameLatin' =>
                [
                    'required',
                    'string'
                ],
            'passportNumber' =>
                [
                    'required',
                    'string'
                ],
            'passportSeries' =>
                [
                    'required',
                    'string'
                ],
            'issuedBy' =>
                [
                    'required',
                    'string'
                ],
            'issuesDate' =>
                [
                    'required',
                    'date'
                ],
            'addressReg' =>
                [
                    'required',
                    'string'
                ],
            'migrationCardRequisite' =>
                [
                    'required',
                    'string'
                ],
            'citizenship' =>
                [
                    'required',
                    'string'
                ],
            'phone' =>
                [
                    'required',
                    'string'
                ]
        ];
    }


}
