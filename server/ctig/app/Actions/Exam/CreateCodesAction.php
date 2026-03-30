<?php

namespace App\Actions\Exam;

use App\Actions\Exam\Validation\EnsureExamHasEnrollmentAction;
use App\Actions\Exam\Validation\EnsureExamIsNotCancelledAction;
use App\Actions\Exam\Validation\EnsureExamIsNotCompletedAction;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Barryvdh\DomPDF\Facade\Pdf;

final class CreateCodesAction{
    public function __construct(
        protected EnsureExamIsNotCancelledAction $ensureExamIsNotCancelled,
        protected EnsureExamIsNotCompletedAction $ensureExamIsNotCompleted,
        protected EnsureExamHasEnrollmentAction $ensureExamHasEnrollment
    ){}
    public function execute(Exam $exam){
        $this->ensureExamIsNotCompleted->execute($exam);
        $this->ensureExamIsNotCancelled->execute($exam);
        $this->ensureExamHasEnrollment->execute($exam);

        $examBeginTime = Carbon::parse($exam->begin_time);
        $minutesBieforeBegin = $examBeginTime->copy()->diffInMinutes(Carbon::now(), false);

        // $minutes = config('exam.code_generation_before_minutes'); 
        // if(-$minutesBieforeBegin >= $minutes){
        //     throw new BusinessException("Коды можно сформировать минимум за $minutes минут до экзамена");
        // }

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