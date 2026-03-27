<?php

namespace App\Http\Controllers\Web\Exam;

use App\Actions\Exam\Enrollment\CancellEnrollmentAction;
use App\Actions\Exam\Enrollment\CreateEnrollmentAction;
use App\Actions\Exam\Enrollment\TransferEnrollmentActon;
use App\Models\Exam;
use App\Models\ForeignNational;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExamEnrollmentController
{
    public function store(
                            Request $request,
                            Exam $exam, 
                            CreateEnrollmentAction $createEnrollment,
                            
                        ){ 
        $request->validate([
            'foreignNationalId' => ['required', 'integer', 'min:1'],
        ]);
        $foreignNational = $createEnrollment->execute($exam, request()->input('foreignNationalId'), $request->user()); 
        Inertia::flash([
            'success' => 'Запись успешно создана',
            'redirectUrl' => route('foreign_nationals.application-forms', [
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

    public function transfer(Exam $exam, ForeignNational $foreignNational, TransferEnrollmentActon $transferEnrollment){
        request()->validate([
            'newExamId' => ['required', 'integer', 'min:1'],
        ]);
        $transferEnrollment->exectute($exam, $foreignNational, request()->user());
        return response()->noContent();
    }
}
