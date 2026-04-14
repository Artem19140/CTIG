<?php

namespace App\Domain\ExamDocument;

use App\Exceptions\BusinessException;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Barryvdh\DomPDF\Facade\Pdf;

final class GenerateCodesAction{
    public function __construct(
        protected ExamDocumentAvailable $examDocumentAvailable
    ){}
    public function execute(Exam $exam){
        $this->examDocumentAvailable->codes($exam);
        if(Carbon::now()->diff($exam->begin_time_utc)->minutes >= 60){
            throw new BusinessException("Коды формируются минимум за 60 минут до экзамена");
        }

        $exam->load('enrollments.foreignNational');
        foreach($exam->enrollments as $enrollment){
            if($enrollment->exam_code && $enrollment->exam_id === $exam->id){
                continue;
            }
            
            do{
                $rnd = random_int(1, 999999);
                $code = str_pad($rnd, 6, '0', STR_PAD_LEFT);
                $saved = false;
                
                try{
                    $enrollment->exam_code = $code;
                    $enrollment->exam_id = $exam->id;
                    $enrollment->exam_code_expired_at = Carbon::now()->addMinutes(90);
                    $enrollment->save();
                    $saved = true;
                }catch(QueryException $e){
                    $saved = false;
                }
            }while(!$saved);

        }
        $pdf = Pdf::loadView('templates.exam-codes', [
            'enrollments' => $exam->enrollments,
            'exam' => $exam
        ]);

        return $pdf->stream('codes.pdf');
    }
}