<?php

namespace App\Http\Controllers\Web\Exam;

use App\Actions\Exam\Documents\GenerateExamProtocolAction;
use App\Actions\Exam\Documents\GenerateExamStatementAction;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Validation\ExamDocumentAvailable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Actions\Exam\Documents\GenerateCodesAction;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExamDocumentController
{
    public function __construct(
        protected ExamDocumentAvailable $examDocumentAvailable
    ){}
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
        Gate::authorize('exam-manage-access', $exam);
        return $generateExamProtocol->execute($exam, $request->user() );
    }

    public function protocolAvailable(Exam $exam)
    {
        Gate::authorize('exam-manage-access', $exam);
        $this->examDocumentAvailable->protocol($exam);
        return Inertia::flash([
            'redirectUrl' => route('exam.documents.protocol', ['exam' => $exam])
        ])->back();
    }

    public function statement(Request $request, Exam $exam, GenerateExamStatementAction $generateExamStatement)
    {
        Gate::authorize('exam-manage-access', $exam);
        $spreadSheet = $generateExamStatement->execute($exam);
        
        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');
        
        $fileName = "statement.xlsx";
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment;filename=$fileName",
            'Cache-Control' => 'max-age=0',
        ];
        
        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, $headers);
    }

    public function statementAvailable(Exam $exam)
    {
        Gate::authorize('exam-manage-access', $exam);
        $this->examDocumentAvailable->statement($exam);
        return Inertia::flash([
            'redirectUrl' => route('exam.documents.statement', ['exam' => $exam])
        ])->back();
    }
    
}
