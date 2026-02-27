<?php

namespace App\Http\Controllers\Report;

use App\Actions\Reports\GenerateCertificatesFRDOAction;
use App\Actions\Reports\GenerateExamStatementAction;
use App\Actions\Reports\GenerateReferencesFRDOAction;
use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function frdo(
                        Request $request,
                        GenerateCertificatesFRDOAction $generateCertificatesFRDO,
                        GenerateReferencesFRDOAction $generateReferencesFRDO
                        ){
        $request->validate([
            'date' => ['required', 'date'],
            'isPassedAttempts' => ['required', 'boolean']
        ]);
        $examDate = Carbon::parse($request->input('date'));
        $unCheckedAttempts = Attempt::where('finished_at', '>=',$examDate->copy()->startOfDay())
                                    ->where('finished_at', '<=',$examDate->copy()->endOfDay())
                                    ->whereIn('status', AttemptStatus::unChecked())
                                    ->exists();
        $eD = $examDate->format('d.m.Y');
        if($unCheckedAttempts){
            
            throw new BusinessException("За $eD не все попытки проверены" );
        }

        $isPassedAttempts=$request->input('isPassedAttempts');
        if($isPassedAttempts){
            $spreadsheet = $generateCertificatesFRDO->execute($examDate);
            $fileName = "cerftificates_frdo_$eD.xlsx";
        }else{
            $spreadsheet = $generateReferencesFRDO->execute($examDate);
            $fileName = "references_frdo_$eD.xlsx";
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment;filename=$fileName",
            'Cache-Control' => 'max-age=0',
        ];

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, $headers);
    }

    public function statement(
                                Exam $exam, 
                                GenerateExamStatementAction $generateExamStatement
                            ){
        $writer = $generateExamStatement->execute($exam);
        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });
        $examName = "statement.xlsx";
        // Заголовки для скачивания
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', "attachment;filename=$examName");
        $response->headers->set('Cache-Control','max-age=0');
        return $response;
    }
}
