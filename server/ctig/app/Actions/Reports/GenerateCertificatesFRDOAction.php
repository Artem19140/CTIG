<?php

namespace App\Actions\Reports;

use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;


class GenerateCertificatesFRDOAction{

    public function execute($examDate){
        $templatePath = storage_path('app/templates/certificates_frdo.xlsx');
        $attempts = Attempt::with(['exam.examType','student','exam.address'])
                            ->where('finished_at', '>=',$examDate->copy()->startOfDay())
                            ->where('finished_at', '<=',$examDate->copy()->endOfDay())
                            ->where('is_passed',true)
                            ->where('status', AttemptStatus::Checked)
                            ->get();
        if($attempts->isEmpty()){
            throw new BusinessException('Данных для сертификатов нету');
        }
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A3', 'Иван Иванов');
        $row = 3;
        foreach($attempts as $attempt){
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
            $sheet->setCellValue("J$row", $passportData);
            $sheet->setCellValue("K$row", $attempt->student->citizenship);
            $sheet->setCellValue("M$row", $attempt->exam->address->address);
            $sheet->setCellValue("N$row", config('organization.addressOfIssuingCertificates'));
            $sheet->setCellValue("O$row", config('organization.director'));
            $row++;
        }
        return $spreadsheet;
    }
    
}