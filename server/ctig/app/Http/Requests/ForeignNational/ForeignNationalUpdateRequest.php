<?php

namespace App\Http\Requests\ForeignNational;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;

class ForeignNationalUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $countries = collect(json_decode(file_get_contents(storage_path('app/public/countries.json')), true))
                    ->pluck('value')
                    ->toArray();
        return [
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
            'gender' => [
                    'required', 
                    'string', 
                    'size:1', 
                    'in:M,F'
                ],
            'comment' => [
                    'nullable', 
                    'string', 
                ],
            'addressReg' => [
                'required', 
                'string'
            ],
            'passportTranslateScan' => [
                    'nullable',
                    File::types(['pdf'])->max(4096)
                ],
            'passportScan' => [
                    'nullable', 
                    File::types(['pdf'])->max(4096)
                ],
        ];
    }
}
