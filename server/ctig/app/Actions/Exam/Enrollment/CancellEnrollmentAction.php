<?php

namespace App\Actions\Exam\Enrollment;

use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\ForeignNational;

class CancellEnrollmentAction{
    public function execute(Exam $exam, ForeignNational $foreignNational) {
        
        $isEnrollmentExists = $exam->foreignNationals()->where('foreign_national_id', $foreignNational->id)->exists();
        if(!$isEnrollmentExists){
            throw new BusinessException('У ИГ нет записи на этот экзамен');
        }

        if($exam->isCompleted() || $exam->isGoing()){
            throw new BusinessException('Невозможно отменить запись на прошедший или идущий экзамен');
        }
        $exam->foreignNationals()->detach($foreignNational->id);
    }
}