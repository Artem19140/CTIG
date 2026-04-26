<?php

namespace App\Http\Controllers\Web\Enrollment;

use App\Domain\Enrollment\Action\CancellEnrollmentAction;
use App\Domain\Enrollment\Action\ChangePaymentStatusAction;
use App\Domain\Exam\Query\GetAvailableExamsQuery;
use App\Domain\Enrollment\Action\CreateEnrollmentAction;
use App\Http\Requests\Enrollment\EnrollmentAvailableRequest;
use App\Http\Requests\Enrollment\EnrollmentStoreRequest;
use App\Http\Resources\Enrollment\EnrollmentResource;
use App\Models\Enrollment;
use Inertia\Inertia;

class EnrollmentController
{
    public function store(
        EnrollmentStoreRequest $request,
        CreateEnrollmentAction $createEnrollmentAction,
    ){ 
        $enrollment = $createEnrollmentAction->execute(
            $request->validated('examId'), 
            $request->validated('foreignNationalId'), 
            $request->user(), 
            $request->validated('hasPayment')
        ); 

        return response()->json([
            'redirectUrl' => route('enrollments.statements', ['enrollment' => $enrollment])
        ]);
    }

    public function destroy(Enrollment $enrollment, CancellEnrollmentAction $cancellErollmentAction)
    {
        $cancellErollmentAction->execute($enrollment);

        return Inertia::flash([
            'success' => 'Запись отменена'
        ])->back();
    }

    public function changePayment(
        Enrollment $enrollment,
        ChangePaymentStatusAction $changePaymentStatusAction
    ){
        $changePaymentStatusAction->execute($enrollment);
        // return back();
        return response()->json();
    }

    public function available(
        EnrollmentAvailableRequest $request, 
        GetAvailableExamsQuery $getAvailableExamsQuery
    ){
        
        $exams = $getAvailableExamsQuery->execute(
            $request->validated('examTypeId'), 
            $request->validated('foreignNationalId')
        );

        return $exams->map(function ($exam) {
            return [
                'id' => $exam->id,
                'beginTime' => $exam->begin_time->format('H:i d.m.Y'),
            ];
        });
    } 
}
