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
            'hasPatronymic' =>
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
                    'prohibited_if_accepted:hasPatronymic',
                    'required_if_declined:hasPatronymic',
                    'nullable',
                    'string'
                ],
            'patronymicLatin' =>
                [
                    'prohibited_if_accepted:hasPatronymic',
                    'required_if_declined:hasPatronymic',
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
                    'nullable',
                    'string'
                ],
            'passportSeries' =>
                [
                    'nullable',
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
                    'string',
                    'max:2',
                    'min:2'
                ],
            'phone' =>
                [
                    'required',
                    'string'
                ]
        ];
    }


}
