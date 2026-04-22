<?php

namespace App\Http\Controllers\Web\Report;

use App\Domain\Attempt\Action\CloseAbandonedAttemptsAction;
use App\Domain\Report\CheckAvailableFrdoGenerateAction;
use App\Domain\Report\GenerateFlatTableAction;
use App\Domain\Report\GenerateFRDOReportsAction;
use App\Http\Requests\Report\FlatTableRequest;
use App\Http\Requests\Report\FrdoReportRequest;
use Carbon\Carbon;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;


class ReportController
{
    public function frdo(
                        FrdoReportRequest $request,
                        GenerateFRDOReportsAction $generateFRDOReports
                        ){
        
        $success = $request->validated('success');
        $examDate = Carbon::parse($request->validated('examDate'));
        $writer = $generateFRDOReports->execute(
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
                                CheckAvailableFrdoGenerateAction $checkAvailableGenerate, 
                                CloseAbandonedAttemptsAction $closeAbandonedAttemptsAction
                            ){
        $closeAbandonedAttemptsAction->execute($request->user()->time_zone);
        $checkAvailableGenerate->execute($request->input('examDate'));
        return Inertia::flash([
            'redirectUrl' => route('reports.frdo', [
                'examDate' => $request->validated('examDate'),
                'success' => $request->validated('success')
            ])
        ])->back();
    }

    public function flatTable(FlatTableRequest $request, GenerateFlatTableAction $generateFlatTable){
        return response()->streamDownload(function () use ($generateFlatTable, $request) {

            $generateFlatTable->execute( $request->validated('dateFrom'), $request->validated('dateTo'));
 
        }, 'report.csv');  
    }
}
