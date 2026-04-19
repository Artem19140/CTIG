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
        $enrollment->load(['foreignNational', 'exam.type', 'creator', 'center']);

        return Pdf::loadView('templates.pdf.enrollment.enrollment-full',[
            'enrollment' => $enrollment
        ]);  
    }
}