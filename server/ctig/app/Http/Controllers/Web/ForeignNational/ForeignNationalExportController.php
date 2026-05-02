<?php

namespace App\Http\Controllers\Web\ForeignNational;

use App\Domain\ForeignNational\Query\ExportForeignNationalQuery;
use App\Exceptions\BusinessException;
use App\Http\Requests\ForeignNational\ForeignNationalExportRequest;
use App\Models\ForeignNational;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ForeignNationalExportController
{
    public function exportAvailable(ForeignNationalExportRequest $request){

        $this->ensureExportAvailable($request->validated('dateFrom'), $request->validated('dateTo'), $request->validated('citizenship'));
        return response()->json([
            'redirectUrl' => route('foreign-nationals.export', [
                'dateFrom' => $request->validated('dateFrom'),
                'dateTo' => $request->validated('dateTo'),
                'citizenship' => $request->validated('citizenship'),
            ])
        ]);
    }

    public function export(
        ForeignNationalExportRequest $request,
        ExportForeignNationalQuery $exportForeignNationalQuery
    ){
        //Токо директор
        $this->ensureExportAvailable(
            $request->validated('dateFrom'), 
            $request->validated('dateTo'), 
            $request->validated('citizenship')
        );
        return response()->streamDownload(function () use ($exportForeignNationalQuery, $request) {
            $exportForeignNationalQuery->execute($request->validated());
        }, 'Выгрузка_ИГ.csv',
        [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    protected function ensureExportAvailable(
        string $dateFrom, 
        string $dateTo, 
        string | null $citizenship = null
    ){
        $available = ForeignNational::whereBetween('created_at', [
            Carbon::parse($dateFrom), 
            Carbon::parse($dateTo)
        ])
        ->when($citizenship, function (Builder $query) use($citizenship){
            $query->where('citizenship', $citizenship);
        })
        ->exists();
        if(!$available){
            throw new BusinessException('Нет данных ИГ для выгрузки');
        }
    }
}
