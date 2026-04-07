<?php

namespace App\Domain\ForeignNational\Action;

use App\Domain\ForeignNational\Rules\ForeignNationalValidation;
use App\Models\ForeignNational;
use Storage;

final class UpdateForeignNationalAction{
    public function __construct(
        protected ForeignNationalValidation $foreignNationalValidation
    ){}
    public function execute(array $data, ForeignNational $foreignNational){
        $this->foreignNationalValidation->ensureAge($data['dateBirth']);
        $this->foreignNationalValidation->ensureUniquePassport($data);
        $foreignNational->update(
            $this->attributes($data)
        );
        $foreignNational->save();
    }
    protected function attributes(array $data):array{
        return [
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
            'issued_date'=> $data['issuedDate'],
            'address_reg'=> $data['addressReg'],
            'citizenship'=> $data['citizenship'],
            'phone'=> $data['phone'],
            'gender' => $data['gender'],
            'comment' => $data['comment'] ?? '',
            $this->files($data)
        ];
    }

    protected function files(array $data):array{
        $files = [];
        if($data['passportScanPath']){
            $files['passport_scan'] = Storage::putFile('avatars', $data['passportScan']);
        }
        if($data['passportTranslateScan']){
            $files['passport_translate_scan'] = Storage::putFile('avatars', $data['passportTranslateScan']);
        }
        if($data['photo']){
            $files['photo'] = Storage::putFile('avatars', $data['photo']);
        }
        return  $files;
    }
}