<?php

namespace App\Domain\ForeignNational\Query;

use App\Models\ForeignNational;
use App\Models\User;
use App\Support\Log\BusinessLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ExportForeignNationalQuery{
    public function execute(
        Carbon $dateFrom, 
        Carbon $dateTo, 
        string | null $citizenship,
        User $user, 
        
    ){
        $handle = fopen('php://output', 'w');
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, $this->headers());
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
        $this->log($user);
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

    protected function log(User $user){
        BusinessLog::event('foreign_national_export', [
            'user_id' => $user->id
        ]);
    }
}