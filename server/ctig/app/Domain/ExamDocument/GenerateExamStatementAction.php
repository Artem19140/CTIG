<?php

namespace App\Domain\ExamDocument;

use App\Actions\Attempt\GetDetailedAttemptResultsAction;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Validation\ExamValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GenerateExamStatementAction{
    public function __construct(
        protected GetDetailedAttemptResultsAction $getDetailedAttemptResults,
        protected ExamDocumentAvailable $examDocumentAvailable
    ){}
    public function execute(Exam $exam){
        $this->examDocumentAvailable->statement($exam);

        $templatePath = storage_path('app/templates/statement.xlsx');

        $exam->load([
            'foreignNationals.attempts' => function ($query) use ($exam) {
                $query->where('exam_id', $exam->id);
            }
        ]);
        $spreadSheet = IOFactory::load($templatePath);
        $sheet = $spreadSheet->getActiveSheet();
        $examLevel = $exam->examType->level;
        $examCertificateName = $exam->examType->certificate_name;
        $examDate = $exam->begin_time->format('d.m.Y');
        $sheet->setCellValue("A2", "Экзамен на уровень $examLevel - $examCertificateName");
        $sheet->setCellValue("A3", "Сессия № $exam->session / Дата проведения экзамена: $examDate ");
        $row = 6;
        $counter =1 ;
        
        foreach($exam->foreignNationals as $foreignNational){
            $sheet->setCellValue("A$row", $counter);
            $sheet->setCellValue("B$row", $foreignNational->id);
            $sheet->setCellValue("C$row", $foreignNational->surname);
            $sheet->setCellValue("D$row", $foreignNational->name);
            $sheet->setCellValue("E$row", $foreignNational->patronymic);
            $sheet->setCellValue("F$row", $foreignNational->date_birth->format('d.m.Y'));
            $sheet->setCellValue("G$row", $foreignNational->surname_latin);
            $sheet->setCellValue("G$row", $foreignNational->name_latin);
            $sheet->setCellValue("H$row", $foreignNational->patronymic_latin);
            $sheet->setCellValue("I$row", $foreignNational->patronymic_latin);
            $sheet->setCellValue("J$row", $foreignNational->passport_series." ".$foreignNational->passport_number);
            $sheet->setCellValue("K$row", $foreignNational->citizenship);
            $sheet->setCellValue("L$row", $foreignNational->attempts->first()?->started_at->format('H:i') ?? 'н/я');
            $sheet->setCellValue("M$row", $foreignNational->attempts->first()?->finished_at->format('H:i'));
            $sheet->setCellValue("R$row", $foreignNational->attempts->first()?->total_mark);
             
            $sheet->setCellValue("S$row", $this->getDocumentType($foreignNational));
            if(!$foreignNational->attempts->first()){
                continue;
            }
            $blocks = $this->getDetailedAttemptResults->execute($foreignNational->attempts->first());
            foreach($blocks as $block){
                if($block['order'] === 1){
                    $sheet->setCellValue("O$row", $block['answers_mark_sum']);
                }
                if($block['order'] === 2){
                    $sheet->setCellValue("P$row", $block["answers_mark_sum"]);
                }
                if($block['order'] === 3){
                    $sheet->setCellValue("Q$row", $block["answers_mark_sum"]);
                }
                
                foreach($block['subblocks'] as $subblock){
                    if($block['order'] === 1 && $subblock['order'] === 1){
                        $sheet->setCellValue("N$row", $subblock["answers_mark_sum"]);
                    }
                    
                }
            }
        }
        return $spreadSheet;
    }

    protected function getDocumentType(ForeignNational $foreignNational):string{
        $attempt = $foreignNational->attempts->first();
        if(!$attempt){
            return '';
        }
        return $attempt->is_passed ? 'Сертификат' : 'Справка';
    }
}