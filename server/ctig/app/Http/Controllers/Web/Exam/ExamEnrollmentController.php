<?php

namespace App\Http\Controllers\Web\Exam;

use App\Actions\Exam\Enrollment\CancellEnrollmentAction;
use App\Actions\Exam\Enrollment\CreateEnrollmentAction;
use App\Actions\Exam\Enrollment\TransferEnrollmentActon;
use App\Exceptions\BusinessException;
use App\Exceptions\EntityNotFoundExсeption;
use App\Models\Exam;
use App\Models\Student;
use Illuminate\Http\Request;

class ExamEnrollmentController
{
    public function store(
                            Request $request,
                            Exam $exam, 
                            CreateEnrollmentAction $createEnrollment,
                            
                        ){ 
        $request->validate([
            'studentId' => ['required', 'integer', 'min:1'],
        ]);
        $createEnrollment->execute($exam, request()->input('studentId'), $request->user());     
        return back()->with('success', 'Запись успешно создана');
    }

    public function destroy(Exam $exam, Student $student, CancellEnrollmentAction $cancellErollment)
    {
        $cancellErollment->execute($exam, $student);
        return response()->noContent();
    }

    public function transfer(Exam $exam, Student $student, TransferEnrollmentActon $transferEnrollment){
        request()->validate([
            'newExamId' => ['required', 'integer', 'min:1'],
        ]);
        $transferEnrollment->exectute($exam, $student, request()->user());
        return response()->noContent();
    }
}
