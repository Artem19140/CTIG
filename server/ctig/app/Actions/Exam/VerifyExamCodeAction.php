<?php

namespace App\Actions\Exam;

use App\Enums\TokenAbilities;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\Student;
use Carbon\Carbon;
use DB;

class VerifyExamCodeAction{

    public function execute(string $code):Student{
        $student = Student::where('exam_code', $code)
                        ->first();
        if(!$student){
            throw new BusinessException('Код не найден');
        }

        if($student->exam_code_expired_at < Carbon::now()){
            throw new BusinessException('Истек срок действия кода');
        }

        

        // DB::transaction(function () use($student){
        //     $student->exam_code = null;
        //     $student->exam_code_expired_at = null;
        //     $student->save();
        // });
       return $student;
    }
}