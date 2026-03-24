<?php

namespace App\Http\Controllers\Web\Report;

use App\Actions\Reports\GenerateCertificatesFRDOAction;
use App\Actions\Reports\GenerateExamStatementAction;
use App\Actions\Reports\GenerateFlatTableAction;
use App\Actions\Reports\GenerateFRDOReportsAction;
use App\Actions\Reports\GenerateReferencesFRDOAction;
use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Http\Requests\Report\FlatTableRequest;
use App\Http\Requests\Report\FrdoReportRequest;
use App\Models\Attempt;
use App\Models\Exam;
use Carbon\Carbon;
use App\Http\Controllers\Api\Controller;
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

    public function available(FrdoReportRequest $request){
        $isAvailable = false;
        $examDate = Carbon::parse($request->validated('examDate'));
        $exists = Attempt::where('finished_at', '>=',$examDate->copy()->startOfDay())
                            ->where('finished_at', '<=',$examDate->copy()->endOfDay())
                            ->exists();
        if($exists){
            $unchecked = Attempt::where('finished_at', '>=',$examDate->copy()->startOfDay())
                ->where('finished_at', '<=',$examDate->copy()->endOfDay())
                ->whereIn('status', AttemptStatus::unChecked())
                ->exists();
            if(!$unchecked){
                $isAvailable = true;
            }
        }
        return response()->json([
            'available' => $isAvailable
        ], 200);
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
        }, 'report.csv');  
    }
}
