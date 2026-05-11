<?php

namespace App\Domain\Enrollment\Action;

use App\Enums\Event;
use App\Enums\Resource;
use App\Models\Enrollment;
use App\Support\Log\LogActivity;
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
        LogActivity::event(
            event: Event::Generated,
            resource: Resource::Enrollment,
            context:[
            'enrollment_id'=> $enrollment->id
        ]);
    }
}