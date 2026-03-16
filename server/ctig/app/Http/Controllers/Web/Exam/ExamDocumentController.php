<?php

namespace App\Http\Controllers\Web\Exam;

use App\Exceptions\BusinessException;
use App\Models\Exam;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExamDocumentController
{
    public function studentsList(Request $request, Exam $exam){
        $exam->load(['students', 'examType']);
        $exam->students;
        if($exam->students->isEmpty()){
            throw new BusinessException('На экзамен не записано ни одного студента');
        }
        $pdf = Pdf::loadView('templates.exam-students-list', [
            'students' => $exam->students,
            'exam' => $exam
        ]);
        $stringDate = $exam->begin_time->copy()->format('_H:i_d.m.Y_');
        
        return $pdf->stream("students$stringDate.pdf");
    }
}
