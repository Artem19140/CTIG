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
            'surname' => 'required|string',
            'name'=>'required|string',
            'patronymic' => 'prohibited_if_accepted:no_patronymic|required_if_declined:no_patronymic|string',
            'patronymicLatin' => 'prohibited_if_accepted:no_patronymic|required_if_declined:no_patronymic|string',
            'dateBirth'=>'required|date',
            'surnameLatin'=>'required|string',
            'nameLatin'=>'required|string',
            'passportNumber'=>'required|string',
            'passportSeries'=>'required|string',
            'issuedBy'=>'required|string',
            'issuesDate'=>'required|date',
            'addressReg'=>'required|string',
            'migrationCardRequisite'=>'required|string',
            'citizenship'=>'required|string',
            'phone'=>'required|string',
            'no_patronymic' => 'boolean'
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
            'no_patronymic' => 'нет отчества'
        ];
    }


}
