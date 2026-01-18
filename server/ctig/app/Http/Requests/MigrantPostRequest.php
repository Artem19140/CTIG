<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MigrantPostRequest extends FormRequest
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
            'patronymic'=>'required|string',
            'dateBirth'=>'required|date',
            'surnameLatin'=>'required|string',
            'nameLatin'=>'required|string',
            'patronymicLatin'=>'required|string',
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
            'surname' => 'Фамилия на русском',
            'name' => 'Имя на русском',
            'patronymic' => 'Отчетство на русском',
            'dateBirth' => 'Дата рождения',
            'surnameLatin' => 'Фамилия на латинице',
            'nameLatin' => 'Имя на латинице',
            'patronymicLatin' => 'Отчество  на латинице',
            'passportNumber' => 'Номер паспорта',
            'passportSeries' => 'Серия паспорта',
            'issuedBy' => 'Кем выдан',
            'issuesDate' => 'Дата выдачи',
            'addressReg' => 'Адрес регистрации',
            'migrationCardRequisite' => 'Реквизиты миграционной карты',
            'citizenship' => 'Гражданство',
            'phone' => 'Телефон',
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
