<?php

namespace Server\Ctig\App\Domain\Enrollment\Action;

use App\Domain\Enrollment\Guard\EnrollmentGuard;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerateEnrollmentStatementAction{
    public function __construct(
        protected EnrollmentGuard $enrollmentGuard
    ){}
    public function execute(int $examId, ForeignNational $foreignNational, User $user){ 
        $exam = Exam::with(['examType'])->find($examId);
        $this->enrollmentGuard->ensureExists($exam, $foreignNational->id);
        
        $enrollment = Enrollment::for($exam, $foreignNational)->first();

        return Pdf::loadView('templates.statement-foreign-national',[
            'foreignNational' => $foreignNational,
            'exam' => $exam,
            'user' => $user,
            'reg_number' => $enrollment->reg_number,
            'center' => $user->center
        ]);  
    }
}