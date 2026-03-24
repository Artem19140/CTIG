<?php

namespace App\Actions\Reports;

use App\Actions\Attempt\GetDetailedAttemptResultsAction;
use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
use App\Models\Student;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GenerateExamStatementAction{
    public function __construct(
        protected GetDetailedAttemptResultsAction $getDetailedAttemptResults
    ){}
    public function execute($exam){
        $templatePath = storage_path('app/templates/statement.xlsx');

        if(!$exam->isCompleted()){
            throw new BusinessException('Экзамен еще не прошел');
        }
        
        if($exam->students()->count() < 1){
            throw new BusinessException('На экзамене не было ни одного студента');
        }
        
        $attemptsNotChecked = $exam->attempts()
                                ->whereIn('status', AttemptStatus::unChecked())
                                ->exists();

        if($attemptsNotChecked){
            throw new BusinessException('Не все результаты экзамена еще проверены');
        }
        $exam->load([
            'students.attempts' => function ($query) use ($exam) {
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
        
        foreach($exam->students as $student){
            $sheet->setCellValue("A$row", $counter);
            $sheet->setCellValue("B$row", $student->id);
            $sheet->setCellValue("C$row", $student->surname);
            $sheet->setCellValue("D$row", $student->name);
            $sheet->setCellValue("E$row", $student->patronymic);
            $sheet->setCellValue("F$row", $student->date_birth->format('d.m.Y'));
            $sheet->setCellValue("G$row", $student->surname_latin);
            $sheet->setCellValue("G$row", $student->name_latin);
            $sheet->setCellValue("H$row", $student->patronymic_latin);
            $sheet->setCellValue("I$row", $student->patronymic_latin);
            $sheet->setCellValue("J$row", $student->passport_series." ".$student->passport_number);
            $sheet->setCellValue("K$row", $student->citizenship);
            $sheet->setCellValue("L$row", $student->attempts->first()?->started_at->format('H:i') ?? 'н/я');
            $sheet->setCellValue("M$row", $student->attempts->first()?->finished_at->format('H:i'));
            $sheet->setCellValue("R$row", $student->attempts->first()?->total_mark);
             
            $sheet->setCellValue("S$row", $this->getDocumentType($student));
            if(!$student->attempts->first()){
                continue;
            }
            $blocks = $this->getDetailedAttemptResults->execute($student->attempts->first());
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
            //die;
        }
        return $spreadSheet;
    }

    protected function getDocumentType(Student $student):string{
        $attempt = $student->attempts->first();
        if(!$attempt){
            return '';
        }
        return $attempt->is_passed ? 'Сертификат' : 'Справка';
    }
}