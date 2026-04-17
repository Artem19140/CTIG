<?php

namespace App\Http\Controllers\Web\Exam;

use App\Domain\ExamDocument\ExamDocumentAvailable;
use App\Domain\ExamDocument\GenerateCodesAction;
use App\Domain\ExamDocument\GenerateExamProtocolAction;
use App\Domain\ExamDocument\GenerateExamResultsAction;
use App\Models\Exam;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ExamDocumentController
{
    public function __construct(
        protected ExamDocumentAvailable $examDocumentAvailable
    ){}
    public function list(Exam $exam){
        $this->examDocumentAvailable->list($exam);
        $exam->load(['foreignNationals', 'examType']);
        $pdf = Pdf::loadView('templates.exam-foreign_nationals-list', [
            'foreignNationals' => $exam->foreignNationals,
            'exam' => $exam
        ]);
        $stringDate = $exam->begin_time->copy()->format('_H:i_d.m.Y_');
        $name = $exam->examType->short_name;
        return $pdf->stream("список_$name _ $stringDate.pdf");
    }

    public function listAvailable(Exam $exam){
        $this->examDocumentAvailable->list($exam);
        return Inertia::flash([
            'redirectUrl' => route('exam.documents.list', ['exam' => $exam])
        ])->back();
    }

    public function codes(Exam $exam, GenerateCodesAction $generateCodes)
    {
        Gate::authorize('exam-manage-access', $exam);
        return $generateCodes->execute($exam);
    }

    public function codesAvailable(Exam $exam)
    {
        Gate::authorize('exam-manage-access', $exam);
        $this->examDocumentAvailable->codes($exam);
        return Inertia::flash([
            'redirectUrl' => route('exam.documents.codes', ['exam' => $exam])
        ])->back();
    }

    public function protocol(Request $request, Exam $exam, GenerateExamProtocolAction $generateExamProtocol)
    {
        //Gate::authorize('exam-manage-access', $exam);
        return $generateExamProtocol->execute($exam, $request->user() );
    }

    public function protocolAvailable(Exam $exam)
    {
        //Gate::authorize('exam-manage-access', $exam);
        $this->examDocumentAvailable->protocol($exam);
        return Inertia::flash([
            'redirectUrl' => route('exam.documents.protocol', ['exam' => $exam])
        ])->back();
    }

    public function results(Exam $exam, GenerateExamResultsAction $generateExamResults)
    {
        //Gate::authorize('exam-manage-access', $exam);
        $resultsPdf = $generateExamResults->execute($exam);
        $fileName = "Результаты_".$exam->short_name."_".$exam->begin_time->format('H-i_d.m.Y').".pdf";
        return $resultsPdf->stream($fileName);
    }

    public function resultsAvailable(Exam $exam)
    {
        //Gate::authorize('exam-manage-access', $exam);
        $this->examDocumentAvailable->statement($exam);
        return Inertia::flash([
            'redirectUrl' => route('exam.documents.results', ['exam' => $exam])
        ])->back();
    }
}