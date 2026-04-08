<?php

namespace App\Http\Controllers\Web\Enrollment;

use App\Domain\Enrollment\Action\CancellEnrollmentAction;
use App\Domain\Enrollment\Action\ChangePaymentStatusAction;
use App\Domain\Enrollment\Action\TransferEnrollmentActon;
use App\Domain\Exam\Query\GetAvailableExamsQuery;
use App\Domain\Enrollment\Action\CreateEnrollmentAction;
use App\Http\Requests\Enrollment\EnrollmentAvailableRequest;
use App\Http\Requests\Enrollment\EnrollmentStoreRequest;
use App\Http\Requests\Enrollment\EnrollmentTransferRequest;
use App\Models\Exam;
use App\Models\ForeignNational;
use Inertia\Inertia;


class EnrollmentController
{
    public function store(
                            EnrollmentStoreRequest $request,
                            Exam $exam, 
                            CreateEnrollmentAction $createEnrollment,
                        ){ 

        $foreignNational = $createEnrollment->execute($exam, $request->validated('foreignNationalId'), $request->user(), $request->validated('hasPayment')); 

        Inertia::flash([
            'success' => 'Запись успешно создана',
            'redirectUrl' => route('foreign-nationals.application-forms', [
                'foreignNational' =>$foreignNational->id,
                'examId' => $exam->id,
            ])
        ]);
        return back();
    }

    public function destroy(Exam $exam, ForeignNational $foreignNational, CancellEnrollmentAction $cancellErollment)
    {
        $cancellErollment->execute($exam, $foreignNational);
        return back()->with('success','Запись отменена');
    }

    public function transfer(
                                EnrollmentTransferRequest $request,
                                ForeignNational $foreignNational, 
                                TransferEnrollmentActon $transferEnrollment
                            ){
        $transferEnrollment->exectute(
                                        $request->validated('fromExamId'),
                                        $request->validated('toExamId'),
                                        $foreignNational, 
                                        $request->user()  
                                    );
        Inertia::flash([
            'success' => 'Запись перенесена',
            'redirectUrl' => route('foreign-nationals.application-forms', [
                'foreignNational' =>$foreignNational->id,
                'examId' => $request->validated('toExamId'),
            ])
        ]);
        return back();
    }
    public function changePayment(
                                    Exam  $exam, 
                                    ForeignNational $foreignNational, 
                                    ChangePaymentStatusAction $changePaymentStatus
                                ){
        $changePaymentStatus->execute($exam,$foreignNational);
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
}
