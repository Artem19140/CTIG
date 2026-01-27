<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StudentPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'surname' => 'required|string',
            'name'=>'required|string',
            'patronymic'=>'sometimes|string',
            'dateBirth'=>'required|date',
            'surnameLatin'=>'required|string',
            'nameLatin'=>'required|string',
            'patronymicLatin'=>'sometimes|string',
            'passportNumber'=>'required|string',
            'passportSeries'=>'required|string',
            'issuedBy'=>'required|string',
            'issuesDate'=>'required|date',
            'addressReg'=>'required|string',
            'migrationCardRequisite'=>'required|string',
            'citizenship'=>'required|string',
            'phone'=>'required|string'
        ];
    }

    public function attributes(){
        return [
            'surname' => 'фамилия на русском',
            'name' => 'имя на русском',
            'patronymic' => 'отчетство на русском',
            'dateBirth' => 'дата рождения',
            'surnameLatin' => 'фамилия на латинице',
            'nameLatin' => 'имя на латинице',
            'patronymicLatin' => 'отчество  на латинице',
            'passportNumber' => 'номер паспорта',
            'passportSeries' => 'серия паспорта',
            'issuedBy' => 'кем выдан',
            'issuesDate' => 'дата выдачи',
            'addressReg' => 'адрес регистрации',
            'migrationCardRequisite' => 'реквизиты миграционной карты',
            'citizenship' => 'гражданство',
            'phone' => 'телефон',
        ];
    }

    public function messages(){
        return [
            'required' => "Поле :attribute должно быть заполненным",
            'string' => "Поле :attribute должно быть строкой",
            'date' =>  'Поле :attribute должно быть датой'
        ];
    }
}
