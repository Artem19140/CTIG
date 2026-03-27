<?php

namespace App\Actions\Exam;

use App\Models\Exam;
use Carbon\Carbon;
use App\Exceptions\BusinessException;
use Illuminate\Database\QueryException;
use Barryvdh\DomPDF\Facade\Pdf;

final class CreateCodesAction{
    public function execute(Exam $exam){
        if($exam->isCompleted()){
            throw new BusinessException('Экзмен уже прошел');
        }

        //отменен

        $examBeginTime = Carbon::parse($exam->begin_time);
        $minutesBieforeBegin = $examBeginTime->copy()->diffInMinutes(Carbon::now(), false);

        // $minutes = config('exam.code_generation_before_minutes'); 
        // if(-$minutesBieforeBegin >= $minutes){
        //     throw new BusinessException("Коды можно сформировать минимум за $minutes минут до экзамена");
        // }

        $foreignNationalsExists = $exam->foreignNationals()->exists();
        if(!$foreignNationalsExists){
            throw new BusinessException('На экзамен не записано ни одного ИГ');
        }

        $foreignNationals = $exam->foreignNationals;
        
        foreach($foreignNationals as $foreignNational){
            if($foreignNational->exam_code && $foreignNational->exam_id === $exam->id){
                continue;
            }
            
            do{
                $rnd = random_int(1, 9999);
                $code = str_pad($rnd, 4, '0', STR_PAD_LEFT);
                $saved = false;
                
                try{
                    $foreignNational->exam_code = $code;
                    $foreignNational->exam_id = $exam->id;
                    $foreignNational->exam_code_expired_at = Carbon::now()->addHour();
                    $foreignNational->save();
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
            'foreignNationals' => $foreignNationals,
            'exam' => $exam
        ]);

        return $pdf->stream('codes.pdf');
    }

    protected function createCode(){

    }
}