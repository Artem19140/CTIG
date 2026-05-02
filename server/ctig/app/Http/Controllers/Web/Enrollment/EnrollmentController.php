<?php

namespace App\Http\Controllers\Web\Enrollment;

use App\Domain\Enrollment\Action\CancellEnrollmentAction;
use App\Domain\Enrollment\Action\ChangePaymentStatusAction;
use App\Domain\Enrollment\Action\CreateEnrollmentAction;
use App\Http\Requests\Enrollment\EnrollmentStoreRequest;
use App\Models\Enrollment;

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

        return response()->json();
    }

    public function changePayment(
        Enrollment $enrollment,
        ChangePaymentStatusAction $changePaymentStatusAction
    ){
        $changePaymentStatusAction->execute($enrollment);
        return response()->json();
    }
}
