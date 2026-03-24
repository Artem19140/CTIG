<?php

namespace App\Actions\Reports;

use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Models\Attempt;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use App\Actions\Reports\CheckAvailableFrdoGenerateAction;

class GenerateFRDOReportsAction{
    public function __construct(
        protected CheckAvailableFrdoGenerateAction $checkAvailableGenerate
    ){
        
    }
    public function execute(Carbon $examDate, bool $success, Organization $organization): IWriter{
        $this->checkAvailableGenerate->execute($examDate, $success);
        $spreadsheet = $this->generateReport($examDate, $success, $organization);
        return IOFactory::createWriter($spreadsheet, 'Xlsx');
    }


    protected function isAllAttemptsChecked(Carbon $examDate): void{
        $notCheckedAttempts = Attempt::where('finished_at', '>=',$examDate->copy()->startOfDay())
                                    ->where('finished_at', '<=',$examDate->copy()->endOfDay())
                                    ->whereIn('status', AttemptStatus::unChecked())
                                    ->exists();
        $examDate = $examDate->format('d.m.Y');
        if($notCheckedAttempts){
            throw new BusinessException("За $examDate не все попытки проверены" );
        }
    }

    protected function hasAttemptsForReport($examDate, bool $success): Collection{
        
        $attempts = Attempt::with(['exam.examType','student','exam.address'])
                            ->where('finished_at', '>=',$examDate->copy()->startOfDay())
                            ->where('finished_at', '<=',$examDate->copy()->endOfDay())
                            ->where('is_passed',$success)
                            ->where('status', AttemptStatus::Checked)
                            ->get();
                            //echo $attempts;die;
        if($attempts->isEmpty()){
            $name = $success ? 'сертификатов' : 'справок';
            throw new BusinessException("Данных для $name нету");
        }
        return $attempts;
    }

    protected function generateReport($examDate, bool $success, Organization $organization): Spreadsheet{
        $attempts = $this->hasAttemptsForReport($examDate, $success);
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

                // (опционально) копируем высоту строки
                $sheet->getRowDimension($row)
                    ->setRowHeight($sheet->getRowDimension($templateRow)->getRowHeight());
            }
            if($success){
                $markUp = $this->certificateMarkup($attempt, $organization, $row);
            }else{
                $markUp = $this->referencesMarkup($attempt, $organization, $row);
            }
            
            foreach ( $markUp as $key => $value){
                $sheet->setCellValue($key, $value);
                
            }
            $row++;
        }
        return $spreadsheet;
    }

    protected function certificateMarkup($attempt, Organization $organization, int $row): array
    {
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'M', 'N', 'O'];
        $values = [
            $attempt->student->surname,
            $attempt->student->name,
            $attempt->student->patronymic,
            Carbon::parse($attempt->student->date_birth)->format('d.m.Y'),
            Carbon::parse($attempt->exam->begin_time)->year,
            $attempt->exam->examType->certificate_name,
            $attempt->student->surname_latin,
            $attempt->student->name_latin,
            $attempt->student->patronymic_latin,
            $attempt->student->full_passport,
            $attempt->student->citizenship,
            $attempt->exam->address->address,
            $organization->certificates_issue_address,
            $organization->director_fio,
        ];

        $cells = [];
        foreach ($values as $i => $value) {
            $cells[$columns[$i] . $row] = $value;
        }

        return $cells;
    }

    protected function referencesMarkup($attempt, Organization $organization, int $row): array
    {
        $columns = ['A','B','C','D','E','F','G','H','I','J','K','L','N','O','P','Q'];

        $values = [
            $attempt->student->surname,
            $attempt->student->name,
            $attempt->student->patronymic,
            Carbon::parse($attempt->student->date_birth)->format('d.m.Y'),
            Carbon::parse($attempt->exam->date)->year,
            $attempt->exam->examType->certificate_name,
            $attempt->student->surname_latin,
            $attempt->student->name_latin,
            $attempt->student->patronymic_latin,
            'Справка',
            $attempt->student->full_passport,
            $attempt->student->citizenship,
            $attempt->exam->address->address,
            Carbon::parse($attempt->exam->begin_time)->format('d.m.Y'),
            'Неуспешно',
            $organization->director_fio
        ];
        $cells = [];
        foreach ($values as $index => $value) {
            $cells[$columns[$index] . $row] = $value;
        }

        return $cells;
    }
}