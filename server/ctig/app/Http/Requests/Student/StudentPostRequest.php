<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;

class StudentPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        $countries = collect(json_decode(file_get_contents(storage_path('app/public/countries.json')), true))
                    ->pluck('value') // получаем массив всех кодов
                    ->toArray();
        return [ 
            'noPatronymic' =>
                [
                    'required',
                    'boolean'
                ],
            'noPassportNumber' =>
                [
                    'required',
                    'boolean'
                ],
            'noPassportSeries' =>
                [
                    'required',
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
                    'prohibited_if_accepted:noPassportNumber',
                    'required_if_declined:noPassportNumber',
                    'nullable',
                    'string',
                ],
            'passportSeries' =>
                [
                    'prohibited_if_accepted:noPassportSeries',
                    'required_if_declined:noPassportSeries',
                    'nullable',
                    'string'
                ],
            'issuedBy' =>
                [
                    'required',
                    'string'
                ],
            'issuedDate' =>
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
                    'prohibited_if_accepted:noMigrationCard',
                    'required_if_declined:noMigrationCard',
                    'required',
                    'string'
                ],
            'noMigrationCard'=>[
                    'required',
                    'boolean'
                ],
            'citizenship' =>
                [
                    'required',
                    'string',
                    'max:2',
                    'min:2',
                    Rule::in($countries)
                ],
            'phone' =>
                [
                    'required',
                    'string'
                ],
            'examId'=> [
                    'required',
                    'integer', 
                    'min:1'
                ],
            'gender' => [
                    'required', 
                    'string', 
                    'size:1', 
                    'in:M,F'
                ],
            // 'passportScanTranslate' => [
            //         'required',
            //         File::types(['pdf'])->max(4096)
            //     ],
            // 'passportScan' => [
            //         'required', 
            //         File::types(['pdf'])->max(4096) //application/pdf	
            //     ],
        ];
    }


}
