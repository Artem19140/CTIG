<?php

namespace App\Domain\Enrollment\Action;

use App\Domain\Enrollment\Rules\RescheduleEnrollmentRules;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use DB;

class RescheduleEnrollmentActon{
    public function __construct(
        protected CreateEnrollmentAction $createEnrollment,
        protected RescheduleEnrollmentRules $rescheduleEnrollmentRules
    ){}
    public function execute(Enrollment $enrollment, int $toExamId, User $user):Enrollment{
        $enrollment->load(['exam', 'foreignNational']);
        $toExam = Exam::find($toExamId);
        $foreignNational = ForeignNational::find($enrollment->foreign_national_id);
        $this->rescheduleEnrollmentRules->execute($enrollment, $toExam);
        $enrollment = DB::transaction(function () use($toExam, $foreignNational, $user, $enrollment){
            if(!$enrollment->exam->begin_time_utc->isPast()){
                $enrollment->exam->foreignNationals()->detach($foreignNational->id); //Тут нужно сделать softDelete, а мб статус изменить
            } 
            return $this->createEnrollment->execute($toExam, $foreignNational->id, $user, $enrollment->hasPayment());
        });
        return $enrollment;
    }
}