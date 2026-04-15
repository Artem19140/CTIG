<?php

namespace App\Domain\Enrollment\Action;

use App\Domain\Counter\GenerateRegNumberAction;
use App\Domain\Enrollment\Rules\CreateEnrollmentRules;
use App\Domain\ForeignNational\Action\CreateForeignNationalStatementAction;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;


final class CreateEnrollmentAction{
    public function __construct(
        protected CreateForeignNationalStatementAction $createForeignNationalStatement,
        protected GenerateRegNumberAction $generateRegNumber,
        protected CreateEnrollmentRules $createEnrollmentRules
    ){}
    public function execute(Exam $exam, int $foreignNationalId, User $user, bool $hasPayment):Enrollment{
        $foreignNational = ForeignNational::find($foreignNationalId);
        $this->createEnrollmentRules->execute($exam, $foreignNational);
        $enrollment = Enrollment::create([
            'reg_number' => $this->generateRegNumber->execute(),
            'creator_id' => $user->id,
            'center_id' => $user->center_id,
            'has_payment' => $hasPayment,
            'exam_id' => $exam->id,
            'foreign_national_id' => $foreignNational->id
        ]);

        return $enrollment;
    }
}