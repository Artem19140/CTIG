<?php

namespace App\Domain\ExamDocument;

use App\Models\Enrollment;
use App\Models\Exam;
use Illuminate\Database\QueryException;
use Barryvdh\DomPDF\Facade\Pdf;

final class ExamCodesGenerator{
    public function execute(Exam $exam){
        $exam->load('enrollments.foreignNational');

        $this->generateCodesForExam($exam);

        $pdf = Pdf::loadView('templates.exam-codes', [
            'exam' => $exam
        ]);

        $fileName = 'Кода_' . $exam->short_name . '_' . $exam->begin_time->format('H-i_d.m.y') . '.pdf';
        return $pdf->stream($fileName);
    }

    protected function generateCodesForExam(Exam $exam):void{
        foreach($exam->enrollments as $enrollment){
            if($this->codeWasGenerated($enrollment)){
                continue;
            }
            
            $this->generateAndSaveUniqueCode($enrollment, $exam);

        }
    }

    protected function generateAndSaveUniqueCode(Enrollment $enrollment, Exam $exam){
        while (true) {
            try {
                $this->saveCode($enrollment, $this->generateCode(), $exam);
                return;
            } catch (QueryException $e) {
                if ($e->getCode() !== '23505') {
                    throw $e;
                }
            }
        }
    }

    protected function codeWasGenerated(Enrollment $enrollment):bool{
        return $enrollment->exam_code_used_at || $enrollment->exam_code_expired_at;
    }

    protected function generateCode():string{
        $max = (10 ** Exam::CODES_LENGTH) - 1;
        $rnd = random_int(0, $max);
        $code = str_pad($rnd, Exam::CODES_LENGTH, '0', STR_PAD_LEFT);
        return $code;
    }

    protected function saveCode(Enrollment $enrollment, string $code, Exam $exam):void{
        $enrollment->exam_code = $code;
        $enrollment->exam_code_expired_at = $exam->begin_time->copy()->addMinutes(Exam::CODES_TTL_AFTER_BEGIN_MINUTES);
        $enrollment->save();
    }
}