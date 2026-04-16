<?php

namespace App\Domain\ExamDocument;

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

        $exam->load('enrollments.foreignNational');
        foreach($exam->enrollments as $enrollment){
            if($enrollment->exam_code || $enrollment->exam_code_used_at){
                continue;
            }
            
            do{
                $rnd = random_int(1, 999999);
                $code = str_pad($rnd, Exam::CODES_LENGTH, '0', STR_PAD_LEFT);
                $saved = false;
                
                try{
                    $enrollment->exam_code = $code;
                    $enrollment->exam_id = $exam->id;
                    $enrollment->exam_code_expired_at = Carbon::now()->addMinutes(Exam::CODES_TTL);
                    $enrollment->save();
                    $saved = true;
                }catch(QueryException $e){
                    if($e->getCode() === '23505'){
                        $saved = false;
                    }else{
                        throw $e;
                    }
                    
                }
            }while(!$saved);

        }
        $pdf = Pdf::loadView('templates.exam-codes', [
            'enrollments' => $exam->enrollments,
            'exam' => $exam
        ]);
        $fileName = 'Кода_' . $exam->short_name . '_' . $exam->begin_time->format('H-i_d.m.y') . '.pdf';
        return $pdf->stream($fileName);
    }
}