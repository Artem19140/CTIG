<?php

namespace App\Domain\ForeignNational\Query;

use App\Models\ForeignNational;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ExportForeignNationalQuery{
    public function execute(array $data){
        $handle = fopen('php://output', 'w');
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, $this->headers());
        $dateFrom = Carbon::parse($data['dateFrom']);
        $dateTo = Carbon::parse($data['dateTo']);
        $citizenship = $data['citizenship'] ?? null;
        ForeignNational::query()
                        ->select(['id','surname', 'name', 'patronymic', 'citizenship', 'passport_series', 'passport_number'])
                        ->when($citizenship, function(Builder $query)use($citizenship){
                            $query->where('citizenship', $citizenship);
                        })
                        
                        ->whereBetween('created_at', [$dateFrom, $dateTo])
                        ->orderBy('id')
                        ->lazyById(1000)
                        ->each(function ($i) use ($handle) {
                            fputcsv($handle, [
                                $i->surname,
                                $i->name,
                                $i->patronymic,
                                $i->citizenship,
                                $i->passport_series,
                                $i->passport_number,
                            ]);
                        });
        fclose($handle);
    }

    protected function headers(){
        return [
            'Фамилия',
            'Имя',
            'Отчество',
            'Гражданство',
            'Серия паспорта',
            'Номер паспорта'
        ];
    }
}