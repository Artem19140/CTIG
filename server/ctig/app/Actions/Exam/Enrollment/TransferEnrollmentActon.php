<?php

namespace App\Actions\Exam\Enrollment;

use App\Actions\Exam\Validation\EnsureExamIsNotCancelledAction;
use App\Actions\Exam\Validation\EnsureExamIsNotCompletedAction;
use App\Actions\Exam\Validation\EnsureExamIsNotGoingAction;
use App\Exceptions\BusinessException;
use App\Exceptions\EntityNotFoundExсeption;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use DB;

class TransferEnrollmentActon{
    public function __construct(
        protected CreateEnrollmentAction $createEnrollment,
        protected EnsureExamIsNotCancelledAction $ensureExamIsNotCancelled,
        protected EnsureExamIsNotCompletedAction $ensureExamIsNotCompleted,
        protected EnsureExamIsNotGoingAction $ensureExamIsNotGoing
    ){}
    public function exectute(int $oldExamId, int $newExamId, ForeignNational $foreignNational, User $user){
        $oldExam = Exam::find($oldExamId);
        $newExam = Exam::find($newExamId);

        $this->ensureExamIsNotCancelled->execute($oldExam);
        $this->ensureExamIsNotCancelled->execute($newExam);

        $this->ensureExamIsNotCompleted->execute($oldExam);
        $this->ensureExamIsNotCompleted->execute($newExam);
        
        $this->ensureExamIsNotGoing->execute($oldExam);
        $this->ensureExamIsNotGoing->execute($newExam);

        $oldEnrollment = $oldExam->foreignNationals()->where('foreign_national_id', $foreignNational->id)->first();

        if(!$oldEnrollment){
            throw new BusinessException('Такой записи на экзамен не существует');
        }

        if($oldExam->exam_type_id !== $newExam->exam_type_id ){
            throw new BusinessException('Запись можно перенести только на тот же вид экзамена');
        }

        $isEnrollmentExistsNewExam = $newExam->foreignNationals()->where('foreign_national_id', $foreignNational->id)->exists();

        if($isEnrollmentExistsNewExam){
            throw new BusinessException('ИГ уже имеет запись на экзамене, на который Вы хотите его перенести');
        }

        DB::transaction(function () use($newExam, $foreignNational, $oldExam, $user, $oldEnrollment){
            $oldExam->foreignNationals()->detach($foreignNational->id);
            $this->createEnrollment->execute($newExam, $foreignNational->id, $user, $oldEnrollment->pivot->has_payment);
        });
    }
}