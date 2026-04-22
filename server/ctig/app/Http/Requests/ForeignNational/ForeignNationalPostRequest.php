<?php

namespace App\Http\Requests\ForeignNational;

use App\Http\Dto\ForeignNationalStoreDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;


class ForeignNationalPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => preg_replace('/\D/', '', $this->phone),
        ]);
    }
    public function rules(): array
    {
        $countries = collect(json_decode(file_get_contents(storage_path('app/public/countries.json')), true))
                    ->pluck('value')
                    ->toArray();
        return [ 
            'hasPayment' =>
                [
                    'required',
                    'boolean'
                ],
            'noPatronymic' =>
                [
                    'required',
                    'boolean'
                ],
            'noPatronymicLatin' =>
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
                    'prohibited_if_accepted:noPatronymicLatin',
                    'required_if_declined:noPatronymicLatin',
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
                    'string',
                    'size:11'
                ],
            'comment' =>
                [
                    'nullable',
                    'string'
                ],
            'examId'=> [
                    'required',
                    'integer', 
                    'min:1',    
                    'exists:exams,id'
                ],
            'gender' => [
                    'required', 
                    'string', 
                    'size:1', 
                    'in:M,F'
                ],
            'addressReg' => [
                'required', 
                'string'
            ],
            'passportTranslateScan' => [
                    'required',
                    File::types(['pdf'])->max(4096)
                ],
            'passportScan' => [
                    'required', 
                    File::types(['pdf'])->max(4096)
                ],
        ];
    }

    public function dto(): ForeignNationalStoreDTO
    {
        return new ForeignNationalStoreDTO(
            surname: $this->surname,
            name: $this->name,
            patronymic: $this->patronymic,
            dateBirth: $this->dateBirth,

            surnameLatin: $this->surnameLatin,
            nameLatin: $this->nameLatin,
            patronymicLatin: $this->patronymicLatin,

            passportNumber: $this->passportNumber,
            passportSeries: $this->passportSeries,
            issuedBy: $this->issuedBy,
            issuedDate: $this->issuedDate,

            citizenship: $this->citizenship,
            phone: $this->phone,
            addressReg: $this->addressReg,

            gender: $this->gender,
            comment: $this->comment,

            passportScan: $this->file('passportScan'),
            passportTranslateScan: $this->file('passportTranslateScan'),
        );
    }
}
