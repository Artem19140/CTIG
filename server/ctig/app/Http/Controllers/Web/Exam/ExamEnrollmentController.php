<?php

namespace App\Http\Controllers\Web\Exam;

use App\Actions\Exam\Enrollment\CancellEnrollmentAction;
use App\Actions\Exam\Enrollment\ChangePaymentStatusAction;
use App\Actions\Exam\Enrollment\CreateEnrollmentAction;
use App\Actions\Exam\Enrollment\TransferEnrollmentActon;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\ForeignNational;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Actions\Exam\GetAvailableExamsAction;

class ExamEnrollmentController
{
    public function store(
                            Request $request,
                            Exam $exam, 
                            CreateEnrollmentAction $createEnrollment,
                            
                        ){ 
        $request->validate([
            'foreignNationalId' => ['required', 'integer', 'min:1'],
            'hasPayment' => ['required', 'boolean']
        ]);
        $foreignNational = $createEnrollment->execute($exam, $request->input('foreignNationalId'), $request->user(), $request->input('hasPayment')); 
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

    public function transfer(ForeignNational $foreignNational, TransferEnrollmentActon $transferEnrollment){
        request()->validate([
            'newExamId' => ['required', 'integer', 'min:1', 'exists:exams,id'],
            'oldExamId' => ['required', 'integer', 'min:1', 'exists:exams,id'],
        ]);
        $transferEnrollment->exectute(
                                        request()->input('oldExamId'),
                                        request()->input('newExamId'),
                                        $foreignNational, 
                                        request()->user()  
                                    );
        Inertia::flash([
            'success' => 'Запись перенесена',
            'redirectUrl' => route('foreign-nationals.application-forms', [
                'foreignNational' =>$foreignNational->id,
                'examId' => request()->input('newExamId'),
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

    public function available(Request $request, GetAvailableExamsAction $getAvailableExams){
        $request->validate([
            'examTypeId' => ['required', 'integer', 'min:1'],
            'foreignNationalId' => ['nullable', 'integer', 'min:1'],
        ]);
        $exams = $getAvailableExams->execute($request->input('examTypeId'), $request->input('foreignNationalId'));
        return $exams->map(function ($exam) {
            return [
                'id' => $exam->id,
                'beginTime' => $exam->begin_time->format('H:i d.m.Y'),
            ];
        });
    }        
}
