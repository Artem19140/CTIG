<?php

namespace App\Http\Controllers\Web\Exam;

use App\Actions\Exam\Documents\GenerateExamProtocolAction;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Actions\Exam\Documents\GenerateCodesAction;
use Illuminate\Support\Facades\Gate;

class ExamDocumentController
{
    public function foreignNationalsList(Request $request, Exam $exam){
        $exam->load(['foreignNationals', 'examType']);
        $exam->foreignNationals;
        if($exam->foreignNationals->isEmpty()){
            throw new BusinessException('На экзамен не записано ни одного ИГ');
        }
        $pdf = Pdf::loadView('templates.exam-foreign_nationals-list', [
            'foreignNationals' => $exam->foreignNationals,
            'exam' => $exam
        ]);
        $stringDate = $exam->begin_time->copy()->format('_H:i_d.m.Y_');
        $name = $exam->examType->short_name;
        return $pdf->stream("список_$name _ $stringDate.pdf");
    }

    public function codes(Exam $exam, GenerateCodesAction $generateCodes)
    {
        Gate::authorize('exam-manage-access', $exam);
        return $generateCodes->execute($exam);
    }

    public function protocol(Request $request, Exam $exam, GenerateExamProtocolAction $generateExamProtocol)
    {
        Gate::authorize('exam-manage-access', $exam);
        return $generateExamProtocol->execute($exam, $request->user() );
    }
    
}
