<?php

namespace App\Domain\ExamDocument;

use App\Models\Enrollment;
use App\Models\Exam;
use Illuminate\Database\QueryException;
use Barryvdh\DomPDF\Facade\Pdf;

final class ExamCodesGenerator{
    public function __construct(
        protected ExamDocumentAvailable $examDocumentAvailable
    ){}
    public function execute(Exam $exam){
        $this->examDocumentAvailable->codes($exam);
        $exam->load('enrollments.foreignNational');
        foreach($exam->enrollments as $enrollment){
            if($this->codeWasGenerated($enrollment)){
                continue;
            }
            
            do{//Можно вынести в generateCodes, где уже все вызвать
                $code = $this->generateCode();
                $saved = false;
                try{
                    $this->saveCode($enrollment, $code);
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
            'exam' => $exam
        ]);
        $fileName = 'Кода_' . $exam->short_name . '_' . $exam->begin_time->format('H-i_d.m.y') . '.pdf';
        return $pdf->stream($fileName);
    }

    protected function codeWasGenerated(Enrollment $enrollment):bool{
        return $enrollment->exam_code || $enrollment->exam_code_used_at || $enrollment->exam_code_expired_at;
    }

    protected function generateCode():string{
        $max = (10 ** Exam::CODES_LENGTH) - 1;
        $rnd = random_int(0, $max);
        $code = str_pad($rnd, Exam::CODES_LENGTH, '0', STR_PAD_LEFT);
        return $code;
    }

    protected function saveCode(Enrollment $enrollment, string $code):void{
        $enrollment->exam_code = $code;
        $enrollment->exam_code_expired_at = $enrollment->exam->begin_time->addMinutes(Exam::CODES_TTL);
        $enrollment->save();
    }
}