<?php

namespace App\Domain\ForeignNational\Action;


use App\Domain\Enrollment\Action\CreateEnrollmentAction;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\User;
use DB;

class CreateForeignNationalWithEnrollmentAction{
    public function __construct(
        protected StoreForeignNationalAction $storeForeignNational,
        protected CreateEnrollmentAction $createEnrollment,
    ){}
    public function execute(array $foreignNationalData, int $examId, User $user):Enrollment{
        
        $enrollent = DB::transaction(function () use($foreignNationalData, $user, $examId) {
            $foreignNational = $this->storeForeignNational->execute($foreignNationalData, $user->id);
            $exam = Exam::find($examId);
            return $this->createEnrollment->execute($exam, $foreignNational->id, $user, $foreignNationalData['hasPayment'] );
        });//Удалить файлы загруженные при исключении
        return $enrollent;

    }
}