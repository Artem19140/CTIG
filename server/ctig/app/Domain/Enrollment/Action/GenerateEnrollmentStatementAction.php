<?php

namespace App\Domain\Enrollment\Action;

use App\Domain\Enrollment\Guard\EnrollmentGuard;
use App\Models\Enrollment;
use Barryvdh\DomPDF\Facade\Pdf;


class GenerateEnrollmentStatementAction{
    public function __construct(
        protected EnrollmentGuard $enrollmentGuard
    ){}
    public function execute(Enrollment $enrollment):\Barryvdh\DomPDF\PDF{         
        $enrollment->load(['foreignNational', 'exam.examType', 'creator', 'center']);

        return Pdf::loadView('templates.enrollment-statement',[
            'enrollment' => $enrollment
        ]);  
    }
}