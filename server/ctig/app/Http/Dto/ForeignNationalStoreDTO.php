<?php

namespace App\Http\Dto;

use Illuminate\Http\UploadedFile;

class ForeignNationalStoreDTO{
    public function __construct(
        public string $surname,
        public string $name,
        public ?string $patronymic,
        public string $dateBirth,
        public string $surnameLatin,
        public string $nameLatin,
        public ?string $patronymicLatin,
        public string $passportNumber,
        public string $passportSeries,
        public string $issuedBy,
        public string $issuedDate,
        public string $citizenship,
        public string $phone,
        public string $addressReg,
        public string $gender,
        public ?string $comment,
        public UploadedFile $passportScan,
        public UploadedFile $passportTranslateScan,
    ){}
}