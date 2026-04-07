<?php

namespace App\Actions\Exam\Enrollment;

use App\Actions\Counter\GetRegNumberAction;

use App\Exceptions\EntityNotFoundExсeption;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use App\Validation\ExamValidation;
use Carbon\Carbon;
use App\Exceptions\BusinessException;
use App\Actions\ForeignNational\CreateForeignNationalStatementAction;

final class CreateEnrollmentAction{
    public function __construct(
        protected CreateForeignNationalStatementAction $createForeignNationalStatement,
        protected GetRegNumberAction $getRegNumber,
        protected ExamValidation $examValidation
    ){}
    public function execute(Exam $exam, int $foreignNationalId, User $user, bool $hasPayment):ForeignNational{
        $this->examValidation->ensureNotCancelled($exam);
        $this->examValidation->ensureNotCompleted($exam);
        $this->examValidation->ensureNotGoing($exam);
        $foreignNational = ForeignNational::find($foreignNationalId);
        
        if(!$foreignNational){
            throw new EntityNotFoundExсeption('ИГ');
        }
        
        $foreignNationalAge = Carbon::parse($foreignNational->date_birth)->age;
        if($foreignNationalAge < 18){
            throw new BusinessException('Запись возможна только с 18 лет');
        }

        $exam->load(['foreignNationals']);

        $foreignNationals=$exam->foreignNationals;

        if($foreignNationals->contains($foreignNational)){
            throw new BusinessException('Запись уже существует');
        }
        
        if($foreignNationals->count() >= $exam->capacity){
            throw new BusinessException('Запись уже заполена');
        }
            
        $foreignNationalExamsConflict = $foreignNational->exams()->where('begin_time', '<=', $exam->end_time)
                                        ->where('end_time', '>=', $exam->begin_time)
                                        ->where('exams.is_cancelled', false)
                                        ->exists();

        if($foreignNationalExamsConflict){
            throw new BusinessException('На это время у ИГ уже существует запись на другой экзамен');
        }

        $regNumber = $this->getRegNumber->execute();
        
        $exam->foreignNationals()->attach($foreignNational, [
            'reg_number' =>$regNumber, 
            'creator_id' => $user->id,
            'center_id' => $user->center_id,
            'has_payment' => $hasPayment
        ]);
        return $foreignNational;
    }
}