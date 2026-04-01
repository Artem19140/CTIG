<?php

namespace App\Actions\ForeignNational;

use App\Actions\Exam\Enrollment\CreateEnrollmentAction;
use App\Models\Exam;
use App\Models\User;
use DB;

class CreateForeignNationalWithEnrollmentAction{
    public function __construct(
        protected StoreForeignNationalAction $storeForeignNational,
        protected CreateEnrollmentAction $createEnrollment,
    ){}
    public function execute(array $foreignNationalData, int $examId, User $user){
        $exam = Exam::find($examId);
        $foreignNational = DB::transaction(function () use($foreignNationalData, $user, $exam) {
            $foreignNational = $this->storeForeignNational->execute($foreignNationalData, $user->id);
            $this->createEnrollment->execute($exam, $foreignNational->id, $user, $foreignNationalData['hasPayment'] );
            return $foreignNational;
        });//Удалить файлы загруженные при исключении?
        return $foreignNational;

    }
}