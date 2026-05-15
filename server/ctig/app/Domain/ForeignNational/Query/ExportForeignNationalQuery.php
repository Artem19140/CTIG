<?php

namespace App\Domain\ForeignNational\Query;

use App\Domain\Center\CenterContext;
use App\Models\ForeignNational;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class ExportForeignNationalQuery{
    public function execute(
        Carbon $dateFrom, 
        Carbon $dateTo,      
        string | null $citizenship
        
    ){
        $handle = fopen('php://output', 'w');
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, $this->headers());
        $count = 0;
        ForeignNational::query()
            ->forCenter(app(CenterContext::class)->id())
            ->select(['id','surname', 'name', 'patronymic', 'citizenship', 'passport_series', 'passport_number'])
            ->when($citizenship, function(Builder $query)use($citizenship){
                $query->where('citizenship', $citizenship);
            })
            
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('id')
            ->lazyById(1000)
            ->each(function ($i) use ($handle, &$count) {
                fputcsv($handle, [
                    $i->surname,
                    $i->name,
                    $i->patronymic,
                    $i->citizenship,
                    $i->passport_series,
                    $i->passport_number,
                ]);
                $count++;
            });
            
        fclose($handle);
        Log::info('foreign_national_export', [
            'date_from' => $dateFrom->format('d.m.Y'),
            'date_to' => $dateTo->format('d.m.Y'),
            'citizenship' => $citizenship,
            'count' => $count
        ]);

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