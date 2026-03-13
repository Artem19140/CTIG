<?php

namespace App\Actions\Reports;

use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GenerateExamStatementAction{
    public function execute($exam){
        $templatePath = storage_path('app/templates/statement.xlsx');
        if(!$exam->isPassed()){
            throw new BusinessException('Экзамен еще не прошел');
        }

        if($exam->students()->count() < 1){
            throw new BusinessException('На экзамене не было ни одного студента');
        }
        $attemptsNotChecked = $exam->attempts()
                                ->whereIn('status', AttemptStatus::unChecked())
                                ->exists();
        // if($attemptsNotChecked){
        //     throw new BusinessException('Не все результаты экзамена еще проверены');
        // }
        $exam->load([
            'students.attempts' => function ($query) use ($exam) {
                $query->where('exam_id', $exam->id);
            }
        ]);
        $spreadSheet = IOFactory::load($templatePath);
        $sheet = $spreadSheet->getActiveSheet();
        $examLevel = $exam->examType->level;
        $examCertificateName = $exam->examType->certificate_name;
        $examDate = $exam->date->format('d.m.Y');
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
            $sheet->setCellValue("L$row", $student->attempts->first()?->started_at->format('H:m'));
            $sheet->setCellValue("M$row", $student->attempts->first()?->finished_at->format('H:m'));
        }
        return $spreadSheet;
    }
}



// $examType = $exam->examType->name;
//         $examLevel = $exam->examType->level;
//         $examAddress = $exam->address->address;
//         $examCertificateName = $exam->examType->certificate_name;
//         $examDate = $exam->date->format('d.m.Y');
//         $examTime = $exam->begin_time->format('H:i');
//         $sheet = $spreadSheet->getActiveSheet();