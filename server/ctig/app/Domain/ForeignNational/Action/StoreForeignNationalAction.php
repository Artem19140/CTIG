<?php

namespace App\Domain\ForeignNational\Action;

use App\Domain\ForeignNational\Rules\ForeignNationalValidation;
use App\Models\ForeignNational;
use Storage;


final class StoreForeignNationalAction{
    public function __construct(
        protected ForeignNationalValidation $foreignNationalValidation
    ){}
    public function execute(array $data, int $creatorId): ForeignNational{
        $this->foreignNationalValidation->ensureAge($data['dateBirth']);
        $this->foreignNationalValidation->ensureUniquePassport($data);
        return  ForeignNational::create(
            $this->attributes($data, $creatorId),
        );
    }

    private function attributes(array $data, int $creatorId):array{
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
            'creator_id'=>$creatorId,
            'gender' => $data['gender'],
            'comment' => $data['comment'],
            'passport_translate_scan' => Storage::putFile('avatars', $data['passportTranslateScan']),
            'passport_scan' => Storage::putFile('avatars', $data['passportScan']),
            //'photo' => Storage::putFile('avatars', $data['photoScan']);
        ];
    }
}