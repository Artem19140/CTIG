<?php

namespace App\Actions\ForeignNational;

use App\Exceptions\BusinessException;
use App\Models\ForeignNational;
use Carbon\Carbon;
use Storage;

final class StoreForeignNationalAction{
    public function execute(array $data, int $creatorId): ForeignNational{
        $age = Carbon::parse($data['dateBirth'])->age;
         
        if($age < 18){
            throw new BusinessException('На экзамен можно записывать с 18 лет');
        }

        $uniquePassportData = ForeignNational::where("passport_number", $data['passportNumber'])
                            ->where("passport_series", $data['passportSeries'])
                            ->where('citizenship', $data['citizenship'])
                            ->exists();
        if($uniquePassportData){
            throw new BusinessException('ИГ с такими паспортными данными и гражданством уже существует');
        }
        $passportTranslateScan  = Storage::putFile('avatars', $data['passportTranslateScan']);
        //$photoPath  = Storage::putFile('avatars', $data['photoScan']);
        $passportScanPath =  Storage::putFile('avatars', $data['passportScan']); //passport_scan_path
        return  ForeignNational::create([
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
            'passport_scan_path' => $passportScanPath,
            //'photo_path' => $photoPath,
            'passport_translate_scan' => $passportTranslateScan
        ]);
    }
}