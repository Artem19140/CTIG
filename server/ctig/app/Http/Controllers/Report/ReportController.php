<?php

namespace App\Http\Controllers\Report;

use App\Actions\Reports\GenerateCertificatesFRDOAction;
use App\Actions\Reports\GenerateReferencesFRDOAction;
use App\Enums\AttemptStatusEnum;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
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
                                    ->whereIn('status', AttemptStatusEnum::unChecked())
                                    ->exists();

        if($unCheckedAttempts){
            $eD = $examDate->format('d.m.Y');
            throw new BusinessException("За $eD не все попытки проверены" );
        }

        $isPassedAttempts=$request->input('isPassedAttempts');
        $eD = $examDate->format('d.m.Y');
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
}
