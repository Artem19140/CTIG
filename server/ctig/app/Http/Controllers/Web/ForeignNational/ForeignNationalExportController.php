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
        $dateFrom = Carbon::parse($request->validated('dateFrom'));
        $dateTo = Carbon::parse($request->validated('dateTo'));
        $citizenship = $request->validated('citizenship');
        $this->ensureExportAvailable(
            $request->validated('dateFrom'), 
            $request->validated('dateTo'), 
            $citizenship
        );
        $fileName = "Выгрузка_ИГ_{$dateFrom->toDateString()}_{$dateTo->toDateString()}{$citizenship}.csv";
        return response()->streamDownload(function () use ($exportForeignNationalQuery, $request, $dateFrom, $dateTo, $citizenship) {
            
            $exportForeignNationalQuery->execute($dateFrom, $dateTo, $citizenship, $request->user());
        }, $fileName,
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
