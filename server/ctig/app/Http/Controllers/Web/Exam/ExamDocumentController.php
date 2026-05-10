<?php

namespace App\Http\Controllers\Web\Exam;

use App\Domain\ExamDocument\ExamDocumentAvailable;
use App\Domain\ExamDocument\ExamCodesGenerator;
use App\Domain\ExamDocument\ExamProtocolGenerator;
use App\Domain\ExamDocument\ExamResultsGenerator;
use App\Enums\ExamDocuments;
use App\Events\ExamDocumentGenerated;
use App\Models\Exam;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ExamDocumentController
{
    public function __construct(
        protected ExamDocumentAvailable $examDocumentAvailable
    ){}

    public function list(Request $request, Exam $exam){
        $this->examDocumentAvailable->list($exam);
        Gate::authorize('list', $exam);
        
        $exam->load(['foreignNationals', 'type']);
        $pdf = Pdf::loadView('templates.exam-foreign_nationals-list', [
            'foreignNationals' => $exam->foreignNationals,
            'exam' => $exam
        ]);
        $stringDate = $exam->begin_time->copy()->format('_H:i_d.m.Y_');
        $name = $exam->type->short_name;
        event(new ExamDocumentGenerated($exam, $request->user(), ExamDocuments::List));
        return $pdf->stream("список_$name _ $stringDate.pdf");
    }

    public function listAvailable(Exam $exam){
        $this->examDocumentAvailable->list($exam);
        return response()->json([
            'redirectUrl' => route('exam.documents.list', ['exam' => $exam])
        ]);
    }

    public function codes(
        Request $request,
        Exam $exam, 
        ExamCodesGenerator $examCodesGenerator
    ){
        $this->authorize($exam);
        $this->examDocumentAvailable->codes($exam);
        return $examCodesGenerator->execute($exam, $request->user());
    }

    public function codesAvailable(Exam $exam){
        $this->authorize($exam);
        $this->examDocumentAvailable->codes($exam);
        return response()->json([
            'redirectUrl' => route('exam.documents.codes', ['exam' => $exam])
        ]);
    }

    public function protocol(
        Request $request,
        Exam $exam, 
        ExamProtocolGenerator $examProtocolGenerator
    ){
        $this->examDocumentAvailable->protocol($exam);
        return $examProtocolGenerator->execute($exam, $request->user());
    }

    public function protocolAvailable(Exam $exam){
        $this->examDocumentAvailable->protocol($exam);
        return response()->json([
            'redirectUrl' => route('exam.documents.protocol', ['exam' => $exam])
        ]);
    }

    public function results(
        Request $request,
        Exam $exam, 
        ExamResultsGenerator $examResultsGenerator
    ){
        $this->examDocumentAvailable->results($exam);
        $resultsPdf = $examResultsGenerator->execute($exam, $request->user());
        $fileName = "Результаты_".$exam->short_name."_".$exam->begin_time->format('H-i_d.m.Y').".pdf";
        return $resultsPdf->stream($fileName);
    }

    public function resultsAvailable(
        Exam $exam
    ){
        $this->examDocumentAvailable->results($exam);
        return response()->json([
            'redirectUrl' => route('exam.documents.results', ['exam' => $exam])
        ]);
    }

    protected function authorize(Exam $exam){
        Gate::authorize('examiner', $exam);
    }
}