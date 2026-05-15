<?php

namespace App\Domain\ForeignNational\Action;


use App\Domain\Enrollment\Action\CreateEnrollmentAction;
use App\Models\Enrollment;
use App\Models\Employee;
use DB;

class CreateForeignNationalWithEnrollmentAction{
    public function __construct(
        protected StoreForeignNationalAction $storeForeignNational,
        protected CreateEnrollmentAction $createEnrollment,
    ){}
    public function execute(array $foreignNationalData, int $examId, Employee $employee):Enrollment{
        
        $enrollent = DB::transaction(function () use($foreignNationalData, $employee, $examId) {
            $foreignNational = $this->storeForeignNational->execute($foreignNationalData, $employee);
            return $this->createEnrollment->execute($examId, $foreignNational->id, $employee, $foreignNationalData['hasPayment'] );
        });//Удалить файлы загруженные при исключении
        return $enrollent;

    }
}