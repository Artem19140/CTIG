<?php

namespace App\Domain\ForeignNational\Action;

use App\Domain\ForeignNational\Guard\ForeignNationalGuard;
use App\Models\ForeignNational;
use App\Models\User;
use App\Support\Log\BusinessLog;
use Storage;

final class StoreForeignNationalAction{
    public function __construct(
        protected ForeignNationalGuard $foreignNationalGuard
    ){}
    public function execute(array $data, User $user): ForeignNational{
        $this->foreignNationalGuard->ensureAge($data['dateBirth']);
        $this->foreignNationalGuard->ensureUniquePassport($data);
        $foreignNational =  ForeignNational::create(
            $this->attributes($data, $user->id),
        );
        $this->log($foreignNational);
        return $foreignNational;
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
            'issued_date'=> $data['issuedDate'],
            'citizenship'=> $data['citizenship'],
            'phone'=> $data['phone'],
            'address_reg'=> $data['addressReg'],
            'creator_id'=>$creatorId,
            'gender' => $data['gender'],
            'comment' => $data['comment'],
            'passport_translate_scan' => Storage::putFile('avatars', $data['passportTranslateScan']),
            'passport_scan' => Storage::putFile('avatars', $data['passportScan']),
            'surname_normalized' => $this->normalize($data['surname']),
            'name_normalized'=> $this->normalize($data['name']),
            'patronymic_normalized'=> $this->normalize($data['patronymic']),
            'passport_number_normalized'=> $this->normalize($data['passportNumber']),
            'passport_series_normalized'=> $this->normalize($data['passportSeries']),
        ];
    }

    protected function normalize(string | null $value){
        if(!$value) return '';
        $value = trim($value);

        if (class_exists(\Normalizer::class)) {
            $value = \Normalizer::normalize($value, \Normalizer::FORM_C);
        }

        $value = mb_strtolower($value, 'UTF-8');
        return $value;
    }

    protected function log(ForeignNational $foreignNational){
        BusinessLog::event('foreign_national_created', [
            'foreign_national_id'=> $foreignNational->id
        ]);
    }
}