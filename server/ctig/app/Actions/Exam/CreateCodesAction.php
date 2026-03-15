<?php

namespace App\Actions\Exam;

use App\Models\Exam;
use Carbon\Carbon;
use App\Exceptions\BusinessException;
use Illuminate\Database\QueryException;
use Barryvdh\DomPDF\Facade\Pdf;

final class CreateCodesAction{
    public function execute(Exam $exam){
        if($exam->isPassed()){
            throw new BusinessException('Экзмен уже прошел');
        }

        //отменен

        $examBeginTime = Carbon::parse($exam->begin_time);
        $minutesBieforeBegin = $examBeginTime->copy()->diffInMinutes(Carbon::now(), false);

        // $minutes = config('exam.code_generation_before_minutes'); 
        // if(-$minutesBieforeBegin >= $minutes){
        //     throw new BusinessException("Коды можно сформировать минимум за $minutes минут до экзамена");
        // }

        $studentsExists = $exam->students()->exists();
        if(!$studentsExists){
            throw new BusinessException('На экзамен не записано ни одного студента');
        }

        $students = $exam->students;
        
        foreach($students as $student){
            if($student->exam_code || $student->exam_id === $exam->id){
                continue;
            }
            
            do{
                $rnd = random_int(1, 9999);
                $code = str_pad($rnd, 4, '0', STR_PAD_LEFT);
                $saved = false;
                try{
                    $student->exam_code = $code;
                    $student->exam_id = $exam->id;
                    $student->exam_code_expired_at = Carbon::now()->addHour();
                    $student->save();
                    $saved = true;
                }catch(QueryException $e){
                    // if ($e->getCode() !== '23000') {
                    //     throw $e;
                    // }
                    $saved = false;
                }
            }while(!$saved);

        }
        $pdf = Pdf::loadView('templates.exam-codes', [
            'students' => $students,
            'exam' => $exam
        ]);

        return $pdf->stream('codes.pdf');
    }

    protected function createCode(){

    }
}