<?php

namespace App\Actions\Exam;

use App\Enums\ExamStatus;
use App\Models\Address;
use App\Models\Exam;
use App\Models\ExamType;
use Carbon\Carbon;
use App\Exceptions\BusinessException;
use DB;
use Illuminate\Database\Eloquent\Builder;

final class CreateStudentsCodesForExamAction{
    public function execute(Exam $exam){
        if($exam->end_time < Carbon::now()){
            throw new BusinessException('Экзамен уже прошел');
        }

        if($exam->status != ExamStatus::Pending && $exam->status != ExamStatus::Started){
            throw new BusinessException('Коды на данный экзамен сформировать уже нельзя');
        }

        $examBeginTime = Carbon::parse($exam->begin_time);
        $minutesBieforeBegin = $examBeginTime->copy()->diffInMinutes(Carbon::now(), false);
        // if($minutesBieforeBegin <= 40){
        //     throw new BusinessException('Коды можно сформировать минимум за 40 минут до экзамена');
        // }

        $studentsExists = $exam->students()->exists();
        if(!$studentsExists){
            throw new BusinessException('На экзамен не записано ни одного студента');
        }

        $students = $exam->students;
        //$students->load('code');
        $code = 1;
        foreach($students as $student){
            echo $student;

            $student->code()->create([
                'code'=>$code,
                'exam_id' => $exam->id,
                'expired_at' => Carbon::now()->addHour()
            ]);
            $code +=1;
            // $code = $student->code;
            // $isCodeActive = $code->expired_at < Carbon::now();
            // if(!$isCodeActive){
            //     throw new BusinessException('Действие кода истекло');
            // }

        }
        echo $exam->students;
        return $students;
    }

    protected function createCode(){

    }
}