<?php

namespace App\Actions\Reports;

use App\Actions\Attempt\GetDetailedAttemptResultsAction;
use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GenerateExamStatementAction{
    public function execute($exam){
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
        
        $attempts = $exam->attempts()
                        ->where('status', AttemptStatus::Checked)
                        ->get();

        $spreadSheet = new Spreadsheet();

        $examType = $exam->examType->name;
        $examLevel = $exam->examType->level;
        $examAddress = $exam->address->address;
        $examCertificateName = $exam->examType->certificate_name;
        $examDate = $exam->date->format('d.m.Y');
        $examTime = $exam->begin_time->format('H:i');
        $sheet = $spreadSheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Ведомость результатов экзамена по русскому языку как иностранному, истории России и основам законодательства Российской Федерации, проведенного в ФГБОУ ВО «УдГУ»');
        $sheet->setCellValue("A2", "$examCertificateName");
        $sheet->setCellValue("A3", "Название: $examType");
        $sheet->setCellValue("A4", "Уровень: $examLevel");
        $sheet->setCellValue("A5", "Адрес: $examAddress");
        $sheet->setCellValue("A6", "Дата: $examDate");
        $sheet->setCellValue("A7", "Время: $examTime");
        $sheet->setCellValue("A8", "Сессия: $exam->session/Номер: $exam->group");
        $sheet->fromArray($this->headers, NULL, "A9");

        for ($col = 'A'; $col <= 'H'; $col++) {
            $sheet->mergeCells("{$col}9:{$col}10");
            $sheet->getStyle("{$col}9")->getAlignment()->setHorizontal('center');
            $sheet->getStyle("{$col}9")->getAlignment()->setVertical('top');
            $sheet->getStyle("{$col}9")->getFont()->setBold(true);
        }


        $sheet->mergeCells("I9:J9");
        $sheet->getStyle('I9')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $row = 11;
        $exam->load('students.attempts.student');
        foreach($attempts as $attempt){
            $sheet->setCellValue("A$row", $attempt->student->surname);
            $sheet->setCellValue("B$row", $attempt->student->name);
            $sheet->setCellValue("C$row", $attempt->student->patronymic);
            $sheet->setCellValue("D$row", $attempt->student->surname_latin);
            $sheet->setCellValue("E$row", $attempt->student->name_latin);
            $sheet->setCellValue("F$row", $attempt->student->patronymic_latin);
            $sheet->setCellValue("G$row", $attempt->student->passport_series." ".$attempt->student->passport_number);
            $sheet->setCellValue("H$row", $attempt->student->citizenship);
        }

        foreach (range('B', 'Z') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadSheet);
        
        return $writer;
    }

    protected array $headers = [
        'Фамилия',
        'Имя',
        'Отчество',
        'Фамилия лат',
        'Имя лат',
        'Отчество лат',
        'Паспортные данные',
        'Гражданство',
        'Время начала и окончания экзаменов',

    ];
}