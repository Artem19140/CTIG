<?php

namespace App\Actions\Exam;

use App\Enums\TokenAbilities;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\Student;
use Carbon\Carbon;
use DB;

class VerifyExamCodeAction{
    public function execute(string $code):string{
        $student = Student::where('exam_code', $code)
                        ->first();
        if(!$student){
            throw new BusinessException('Код не найден');
        }

        if($student->exam_code_expired_at < Carbon::now()){
            throw new BusinessException('Истек срок действия кода');
        }

        $exam = Exam::find($student->exam_id);

        if($exam->isPassed()){
            throw new BusinessException('Экзмен уже прошел');
        }

        if($exam->begin_time >= Carbon::now()){
            throw new BusinessException('Экзмен еще не начался');
        }
        $token = DB::transaction(function () use($student){
            $student->exam_code = null;
            $student->exam_code_expired_at = null;
            $student->save();
            return $student->createToken(
            TokenAbilities::ExamPrepare->value,
            [TokenAbilities::ExamPrepare->value],
            Carbon::now()->addMinutes(10)
            )->plainTextToken;
        });
        return $token;
    }
}