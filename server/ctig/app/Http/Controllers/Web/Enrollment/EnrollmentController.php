<?php

namespace App\Http\Controllers\Web\Enrollment;

use App\Domain\Enrollment\Action\CancellEnrollmentAction;
use App\Domain\Enrollment\Action\ChangePaymentStatusAction;
use App\Domain\Enrollment\Action\GenerateEnrollmentStatementAction;
use App\Domain\Enrollment\Action\RescheduleEnrollmentActon;
use App\Domain\Exam\Query\GetAvailableExamsQuery;
use App\Domain\Enrollment\Action\CreateEnrollmentAction;
use App\Http\Requests\Enrollment\EnrollmentAvailableRequest;
use App\Http\Requests\Enrollment\EnrollmentStoreRequest;
use App\Http\Requests\Enrollment\EnrollmentRescheduleRequest;
use App\Models\Enrollment;
use App\Models\Exam;
use Inertia\Inertia;


class EnrollmentController
{
    public function store(
                            EnrollmentStoreRequest $request,
                            Exam $exam, 
                            CreateEnrollmentAction $createEnrollment,
                        ){ 

        $enrollment = $createEnrollment->execute($exam, $request->validated('foreignNationalId'), $request->user(), $request->validated('hasPayment')); 
        return Inertia::flash([
            'redirectUrl' => route('enrollments.statements', ['enrollment' => $enrollment])
        ])->back();
    }

    public function destroy(Enrollment $enrollment, CancellEnrollmentAction $cancellErollment)
    {
        $cancellErollment->execute($enrollment);
        return Inertia::flash([
            'success' => 'Запись отменена'
        ])->back();
    }

    public function reschedule(
                                EnrollmentRescheduleRequest $request,
                                Enrollment $enrollment,
                                RescheduleEnrollmentActon $rescheduleEnrollment
                            ){
        $newEnrollment = $rescheduleEnrollment->execute(
                                        $enrollment,
                                        $request->validated('toExamId'), 
                                        $request->user()  
                                    );
        
        return Inertia::flash([
            'redirectUrl' => route('enrollments.statements', ['enrollment' => $newEnrollment])
        ])->back();
    }
    public function changePayment(
                                    Enrollment $enrollment,
                                    ChangePaymentStatusAction $changePaymentStatus
                                ){
        $changePaymentStatus->execute($enrollment);
        return back();
        return response()->noContent();
    }

    public function available(EnrollmentAvailableRequest $request, GetAvailableExamsQuery $getAvailableExamsQuery){
        $exams = $getAvailableExamsQuery->execute($request->validated('examTypeId'), $request->validated('foreignNationalId'));
        return $exams->map(function ($exam) {
            return [
                'id' => $exam->id,
                'beginTime' => $exam->begin_time->format('H:i d.m.Y'),
            ];
        });
    } 
    
    public function statement(Enrollment $enrollment, GenerateEnrollmentStatementAction $generateEnrollmentStatement){
        $statement = $generateEnrollmentStatement->execute($enrollment);
        return $statement->stream('statement.pdf'); 
    }
}
