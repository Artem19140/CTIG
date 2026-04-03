<?php

namespace App\Http\Controllers\Web\Report;

use App\Actions\Reports\CheckAvailableFrdoGenerateAction;
use  App\Actions\Exam\Documents\GenerateExamStatementAction;
use App\Actions\Reports\GenerateFlatTableAction;
use App\Actions\Reports\GenerateFRDOReportsAction;
use App\Http\Requests\Report\FlatTableRequest;
use App\Http\Requests\Report\FrdoReportRequest;
use App\Models\Exam;
use Carbon\Carbon;
use App\Http\Controllers\Api\Controller;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function frdo(
                        FrdoReportRequest $request,
                        GenerateFRDOReportsAction $generateFRDOReports,
                        ){
        
        $success = $request->validated('success');
        $examDate = Carbon::parse($request->validated('examDate'));
        $writer = $generateFRDOReports->execute(
                                                    $examDate,
                                                    $success,
                                                    $request->user()->organization
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

    public function available(FrdoReportRequest $request, CheckAvailableFrdoGenerateAction $checkAvailableGenerate){
        $checkAvailableGenerate->execute($request->input('examDate'));
        return Inertia::flash([
            'url' => route('reports.frdo', [
                'examDate' => $request->validated('examDate'),
                'success' => $request->validated('success')
            ])
        ])->back();
    }

    public function statement(
                                Exam $exam, 
                                GenerateExamStatementAction $generateExamStatement
                            ){
                                
        $spreadSheet = $generateExamStatement->execute($exam);
        
        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');
        
        $fileName = "statement.xlsx";
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment;filename=$fileName",
            'Cache-Control' => 'max-age=0',
        ];
        
        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, $headers);
    }

    public function flatTable(FlatTableRequest $request, GenerateFlatTableAction $generateFlatTable){
        return response()->streamDownload(function () use ($generateFlatTable, $request) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");

            $generateFlatTable->execute( $request->validated('dateFrom'), $request->validated('dateTo'),$handle);

            fclose($handle); 
        }, 'report1.csv');  
    }
}
