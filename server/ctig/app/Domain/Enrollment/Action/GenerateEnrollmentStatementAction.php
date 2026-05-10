<?php

namespace App\Domain\Enrollment\Action;

use App\Models\Enrollment;
use App\Models\User;
use App\Support\Log\BusinessLog;
use Barryvdh\DomPDF\Facade\Pdf;


class GenerateEnrollmentStatementAction{
    public function execute(Enrollment $enrollment, User $user):\Barryvdh\DomPDF\PDF{         
        $enrollment->load(['foreignNational', 'exam.type', 'creator', 'center']);
        $this->log($user, $enrollment);
        return Pdf::loadView('templates.pdf.enrollment.enrollment-full',[
            'enrollment' => $enrollment
        ]);  
    }

    protected function log(User $user, Enrollment $enrollment){
        BusinessLog::event('enrollment_statement_generated', [
            'user_id' => $user->id,
            'enrollment_id'=> $enrollment->id
        ]);
    }
}