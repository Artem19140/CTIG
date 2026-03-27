<?php

namespace App\Actions\ForeignNational;

use App\Exceptions\BusinessException;
use App\Models\ForeignNational;
use Carbon\Carbon;

final class UpdateForeignNationalAction{
    public function execute(array $data, ForeignNational $foreignNational){
        $age = Carbon::parse($data['dateBirth'])->age;
        if($age < 18){
            throw new BusinessException('На экзамен можно записывать с 18 лет');
        }
        $uniquePassportData = ForeignNational::where("passport_number", $data['passportNumber'])
                            ->where("passport_series", $data['passportSeries'])
                            ->where('id', '<>', $foreignNational->id)
                            ->where('citizenship', $data['citizenship'])
                            ->exists();

        if($uniquePassportData){
            throw new BusinessException('ИГ с такими паспортными данными и гражданством уже существует');
        }
        $foreignNational->update(
            [
                'surname' => $data['surname'],
                'name'=> $data['name'],
                'patronymic'=> $data['patronymic'],
                'date_birth'=> $data['dateBirth'],
                'surname_latin'=> $data['surnameLatin'],
                'name_latin'=> $data['nameLatin'],
                'patronymic_latin'=> $data['patronymicLatin'],
                'passport_number'=> $data['passportNumber'],
                'passport_series'=> $data['passportSeries'],
                'issued_by'=> $data['issuedBy'],
                'migration_card_requisite'=> $data['migrationCardRequisite'],
                'issues_date'=> $data['issuedDate'],
                'address_reg'=> $data['addressReg'],
                'citizenship'=> $data['citizenship'],
                'phone'=> $data['phone']
        ]);
    }
}