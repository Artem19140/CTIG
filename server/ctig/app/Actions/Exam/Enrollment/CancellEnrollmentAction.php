<?php

namespace App\Actions\Exam\Enrollment;

use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\Student;

class CancellEnrollmentAction{
    public function execute(Exam $exam, Student $student) {
        
        $isEnrollmentExists = $exam->students()->where('student_id', $student->id)->exists();
        if(!$isEnrollmentExists){
            throw new BusinessException('У студента нет записи на этот экзамен');
        }

        if($exam->isPassed() || $exam->isGoing()){
            throw new BusinessException('Невозможно отменить запись на прошедший или идущий экзамен');
        }
        $exam->students()->detach($student->id);
    }
}