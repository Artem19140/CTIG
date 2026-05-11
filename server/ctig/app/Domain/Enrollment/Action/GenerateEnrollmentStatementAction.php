<?php

namespace App\Domain\Enrollment\Action;

use App\Models\Enrollment;
use App\Support\Log\BusinessLog;
use Barryvdh\DomPDF\Facade\Pdf;


class GenerateEnrollmentStatementAction{
    public function execute(Enrollment $enrollment):\Barryvdh\DomPDF\PDF{         
        $enrollment->load(['foreignNational', 'exam.type', 'creator', 'center']);
        $this->log($enrollment);
        return Pdf::loadView('templates.pdf.enrollment.enrollment-full',[
            'enrollment' => $enrollment
        ]);  
    }

    protected function log(Enrollment $enrollment){
        BusinessLog::event('enrollment_statement_generated', [
            'enrollment_id'=> $enrollment->id
        ]);
    }
}