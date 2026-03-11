<?php

namespace App\Actions\Student;

use App\Exceptions\BusinessException;
use App\Models\Student;
use Carbon\Carbon;

final class UpdateStudentAction{
    public function execute(array $data, Student $student){
        $age = Carbon::parse($data['dateBirth'])->age;
        if($age < 18){
            throw new BusinessException('На экзамен можно записывать с 18 лет');
        }
        $uniquePassportData = Student::where("passport_number", $data['passportNumber'])
                            ->where("passport_series", $data['passportSeries'])
                            ->where('id', '<>', $student->id)
                            ->where('citizenship', $data['citizenship'])
                            ->exists();

        if($uniquePassportData){
            throw new BusinessException('Студент с такими паспортными данными и гражданством уже существует');
        }
        $student->update(
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