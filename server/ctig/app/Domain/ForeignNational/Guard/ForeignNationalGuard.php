<?php

namespace App\Domain\ForeignNational\Guard;

use App\Exceptions\BusinessException;
use App\Models\ForeignNational;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ForeignNationalGuard{
    public function ensureAge(string $dateBirth){
        $age = Carbon::parse($dateBirth)->age;
        
        if($age < 18){
            throw new BusinessException('На экзамен можно записывать с 18 лет');
        }
    }
    public function ensureUniquePassport(array $data, int | null $ignoreId = null){
        $uniquePassportData = ForeignNational::where("passport_number", $data['passportNumber'])
                            ->where("passport_series", $data['passportSeries'])
                            ->when($ignoreId, function(Builder $query)use($ignoreId){
                                $query->where('id','<>', $ignoreId);
                            })
                            ->where('id', '<>', $ignoreId)
                            ->where('citizenship', $data['citizenship'])
                            ->exists();

        if($uniquePassportData){
            throw new BusinessException('ИГ с такими паспортными данными и гражданством уже существует');
        }
    }
        
        
}