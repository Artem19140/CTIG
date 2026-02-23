<?php

namespace App\Actions\Reports;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;


class GenerateReferencesFRDOAction{
    public function execute($examDate){
        $templatePath = storage_path('app/templates/references_frdo.xls');
        $attempts = Attempt::with(['exam.examType','student','exam.address'])
                            ->where('finished_at', '>=',$examDate->copy()->startOfDay())
                            ->where('finished_at', '<=',$examDate->copy()->endOfDay())
                            ->where('is_passed',false)
                            ->where('status', AttemptStatus::Checked)
                            ->get();
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A3', 'Иван Иванов');
        $row = 3;
        foreach($attempts as $attempt){
            //echo var_dump($attempt->exam->examType->certificate_name);
            $sheet->setCellValue("A$row", $attempt->student->surname);
            $sheet->setCellValue("B$row", $attempt->student->name);
            $sheet->setCellValue("C$row", $attempt->student->patronymic);
            $sheet->setCellValue("D$row", Carbon::parse($attempt->student->date_birth)->format('d.m.Y'));
            $sheet->setCellValue("E$row", Carbon::parse($attempt->exam->date)->year);
            $sheet->setCellValue("F$row", $attempt->exam->examType->certificate_name);
            $sheet->setCellValue("G$row", $attempt->student->surname_latin);
            $sheet->setCellValue("H$row", $attempt->student->name_latin);
            $sheet->setCellValue("I$row", $attempt->student->patronymic_latin);
            $passportData = trim($attempt->student->passport_series.' '.$attempt->student->passport_number);
            $sheet->setCellValue("J$row", 'Справка');
            $sheet->setCellValue("K$row", $passportData);
            $sheet->setCellValue("L$row", $attempt->student->citizenship);
            $sheet->setCellValue("N$row", $attempt->exam->address->address);
            $sheet->setCellValue("O$row", Carbon::parse($attempt->exam->date)->format('d.m.Y'));
            $sheet->setCellValue("P$row", 'Неуспешно');
            $sheet->setCellValue("Q$row", config('organization.director'));
            $row++;
        }
        return $spreadsheet;
    }
    
}