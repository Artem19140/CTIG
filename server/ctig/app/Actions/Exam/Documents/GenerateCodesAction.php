<?php

namespace App\Actions\Exam\Documents;

use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Validation\ExamValidation;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Barryvdh\DomPDF\Facade\Pdf;

final class GenerateCodesAction{
    public function __construct(
        protected ExamValidation $examValidation
    ){}
    public function execute(Exam $exam){
        $this->examValidation->ensureNotCompleted($exam);
        $this->examValidation->ensureNotCancelled($exam);
        $this->examValidation->ensureHasEnrollment($exam);
        if(Carbon::now()->diff($exam->begin_time_utc)->minutes >= 40){
            throw new BusinessException("Коды можно сформировать минимум за 40 минут до экзамена");
        }

        $foreignNationals = $exam->foreignNationals;
        
        foreach($foreignNationals as $foreignNational){
            if($foreignNational->exam_code && $foreignNational->exam_id === $exam->id){
                continue;
            }
            
            do{
                $rnd = random_int(1, 999999);
                $code = str_pad($rnd, 6, '0', STR_PAD_LEFT);
                $saved = false;
                
                try{
                    $foreignNational->exam_code = $code;
                    $foreignNational->exam_id = $exam->id;
                    $foreignNational->exam_code_expired_at = Carbon::now()->addHour();
                    $foreignNational->save();
                    $saved = true;
                }catch(QueryException $e){
                    $saved = false;
                }
            }while(!$saved);

        }
        $pdf = Pdf::loadView('templates.exam-codes', [
            'foreignNationals' => $foreignNationals,
            'exam' => $exam
        ]);

        return $pdf->stream('codes.pdf');
    }
}