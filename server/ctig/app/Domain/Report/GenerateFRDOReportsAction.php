<?php

namespace App\Domain\Report;

use App\Domain\Report\CheckAvailableFrdoGenerateAction;
use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Center;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class GenerateFRDOReportsAction{
    public function __construct(
        protected CheckAvailableFrdoGenerateAction $checkAvailableGenerate
    ){
        
    }
    public function execute(string $examDate, bool $success, Center $center): IWriter{
        $examDate = Carbon::parse($examDate);
        $this->checkAvailableGenerate->execute($examDate);
        $spreadsheet = $this->generateReport($examDate, $success, $center);
        return IOFactory::createWriter($spreadsheet, 'Xlsx');
    }

    protected function attemptsForReport($examDate, bool $success): Collection{
        return Attempt::with(['exam.examType','foreignNational','exam.address'])
                    ->where('finished_at', '>=',$examDate->copy()->startOfDay())
                    ->where('finished_at', '<=',$examDate->copy()->endOfDay())
                    ->where('is_passed',$success)
                    ->where('status', AttemptStatus::Checked)
                    ->get();
    }

    protected function generateReport($examDate, bool $success, Center $center): Spreadsheet{
        $attempts = $this->attemptsForReport($examDate, $success);
        if($success){
            $templatePath = storage_path('app/templates/certificates_frdo.xlsx');
        }else{
            $templatePath = storage_path('app/templates/references_frdo.xlsx');
        }
        
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
        $templateRow = 3;
        $row = 3;
        
        foreach($attempts as $attempt){
            if ($row > $templateRow) {
                $lastColumn = $success ? 'O' : 'Q';

                $sheet->duplicateStyle(
                    $sheet->getStyle("A{$templateRow}:{$lastColumn}{$templateRow}"),
                    "A{$row}:{$lastColumn}{$row}"
                );

                $sheet->getRowDimension($row)
                    ->setRowHeight($sheet->getRowDimension($templateRow)->getRowHeight());
            }
            if($success){
                $markUp = $this->certificateMarkup($attempt, $center, $row);
            }else{
                $markUp = $this->referencesMarkup($attempt, $center, $row);
            }
            
            foreach ( $markUp as $key => $value){
                $sheet->setCellValue($key, $value);
                
            }
            $row++;
        }
        return $spreadsheet;
    }

    protected function certificateMarkup($attempt, Center $center, int $row): array
    {
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'M', 'N', 'O'];
        $values = [
            $attempt->foreignNational->surname,
            $attempt->foreignNational->name,
            $attempt->foreignNational->patronymic,
            $attempt->foreignNational->date_birth->format('d.m.Y'),
            $attempt->exam->begin_time->year,
            $attempt->exam->examType->certificate_name,
            $attempt->foreignNational->surname_latin,
            $attempt->foreignNational->name_latin,
            $attempt->foreignNational->patronymic_latin,
            $attempt->foreignNational->full_passport,
            $attempt->foreignNational->citizenship,
            $attempt->exam->address->address,
            $center->certificates_issue_address,
            $center->director_fio,
        ];

        $cells = [];
        foreach ($values as $i => $value) {
            $cells[$columns[$i] . $row] = $value;
        }

        return $cells;
    }

    protected function referencesMarkup($attempt, Center $center, int $row): array
    {
        $columns = ['A','B','C','D','E','F','G','H','I','J','K','L','N','O','P','Q'];

        $values = [
            $attempt->foreignNational->surname,
            $attempt->foreignNational->name,
            $attempt->foreignNational->patronymic,
            $attempt->foreignNational->date_birth->format('d.m.Y'),
            $attempt->exam->date->year,
            $attempt->exam->examType->certificate_name,
            $attempt->foreignNational->surname_latin,
            $attempt->foreignNational->name_latin,
            $attempt->foreignNational->patronymic_latin,
            'Справка',
            $attempt->foreignNational->full_passport,
            $attempt->foreignNational->citizenship,
            $attempt->exam->address->address,
            $attempt->exam->begin_time->format('d.m.Y'),
            'Неуспешно',
            $center->director_fio
        ];
        $cells = [];
        foreach ($values as $index => $value) {
            $cells[$columns[$index] . $row] = $value;
        }

        return $cells;
    }
}