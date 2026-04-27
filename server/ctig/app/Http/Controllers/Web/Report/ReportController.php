<?php

namespace App\Http\Controllers\Web\Report;

use App\Domain\Attempt\Action\CloseAbandonedAttemptsAction;
use App\Domain\Report\CheckAvailableFrdoGeneration;
use App\Domain\Report\FlatTableGenerator;
use App\Domain\Report\FRDOReportsGenerator;
use App\Http\Requests\Report\FlatTableRequest;
use App\Http\Requests\Report\FrdoReportRequest;
use Carbon\Carbon;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;


class ReportController
{
    public function frdo(
        FrdoReportRequest $request,
        FRDOReportsGenerator $frdoGenerator
    ){
        $success = $request->validated('success');
        $examDate = Carbon::parse($request->validated('examDate'));
        $writer = $frdoGenerator->execute(
            $examDate,
            $success,
            $request->user()->center
        );
        $stringDate = $examDate->format('d.m.Y');
        $fileName =  $success ? "cerftificates_frdo_$stringDate.xlsx" : "references_frdo_$stringDate.xlsx";

        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment;filename=$fileName",
            'Cache-Control' => 'max-age=0',
        ];

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, $headers);
    }

    public function available(
        FrdoReportRequest $request, 
        CheckAvailableFrdoGeneration $checkAvailableGeneration, 
        CloseAbandonedAttemptsAction $closeAbandonedAttemptsAction
    ){
        $closeAbandonedAttemptsAction->execute($request->user()->time_zone);
        $checkAvailableGeneration->execute($request->input('examDate'), $request->input('success'));
        return response()->json([
            'redirectUrl' => route('reports.frdo', [
                'examDate' => $request->validated('examDate'),
                'success' => $request->validated('success')
            ])
        ]);
    }

    public function flatTable(
        FlatTableRequest $request, 
        FlatTableGenerator $flatTableGenerator
    ){
        $dateFrom = Carbon::parse($request->validated('dateFrom'));
        $dateTo = Carbon::parse($request->validated('dateTo'));
        $fileName =  "Плоская_таблица". "_". $dateFrom->format('d.m.Y') . "_" . $dateTo->format('d.m.Y') . ".csv";
        return response()->streamDownload(function () use ($flatTableGenerator, $dateFrom, $dateTo) {
            
            $flatTableGenerator->execute( 
                $dateFrom, 
                $dateTo
            );
 
        },$fileName,
        [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);  
    }
}
