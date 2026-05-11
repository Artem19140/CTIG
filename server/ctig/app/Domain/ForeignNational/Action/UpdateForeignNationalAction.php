<?php

namespace App\Domain\ForeignNational\Action;

use App\Domain\ForeignNational\Guard\ForeignNationalGuard;
use App\Models\ForeignNational;
use App\Models\User;
use App\Support\Log\BusinessLog;
use Storage;

final class UpdateForeignNationalAction{
    public function __construct(
        protected ForeignNationalGuard $foreignNationalGuard
    ){}
    public function execute(
        array $data, 
        ForeignNational $foreignNational,
    ):ForeignNational{
        $this->foreignNationalGuard->ensureAge($data['dateBirth']);
        $this->foreignNationalGuard->ensureUniquePassport($data, $foreignNational->id);
        $foreignNational->update(
            $this->attributes($data)
        );
        $foreignNational->save();
        $this->log($foreignNational);
        return $foreignNational;
    }
    protected function attributes(array $data):array{
        return array_merge([
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
            'gender' => $data['gender'],
            'address_reg'=> $data['addressReg'],
            'comment' => $data['comment'] ?? '',
        ],
        $this->files($data));
    }

    protected function files(array $data):array{
        $files = [];
        if($data['passportScan'] ?? false){
            //удалить старые файлы
            $files['passport_scan'] = Storage::putFile('avatars', $data['passportScan']);
        }
        if($data['passportTranslateScan'] ?? false){
            //удалить старые файлы
            $files['passport_translate_scan'] = Storage::putFile('avatars', $data['passportTranslateScan']);
        }
        return  $files;
    }

    protected function log(ForeignNational $foreignNational){
        BusinessLog::event('foreign_national_updated', [
            'foreign_national_id'=> $foreignNational->id
        ]);
    }
}