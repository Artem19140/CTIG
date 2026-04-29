<?php

namespace App\Domain\Report;

use App\Models\Attempt;
use App\Support\Export\CsvWriter;
use Carbon\Carbon;

class MinistryEducationReportGenerator
{
    public function __construct(
        protected CsvWriter $csvWriter
    ){}
    public function execute(
        Carbon $dateFrom,
        Carbon $dateTo,
    ){
        $this->csvWriter->setHeaders($this->headers());
        $this->writeRows($dateFrom, $dateTo);
        //$this->csvWriter->writeBom();
    }

    protected function headers():array{
        return [
            'Серия и номер документа, удостоверяющего личность гражданина (без пробелов)',
            'Дата проведения экзамена',
            'Статус сдачи',
            'Экзамен на уровень, соответствующий цели получения:'
        ];
    }

    protected function writeRows(Carbon $dateFrom, Carbon $dateTo){
        Attempt::select(['id', 'foreign_national_id', 'exam_id', 'is_passed'])
            ->whereCreatedAtMore($dateFrom)
            ->whereCreatedAtLess($dateTo)
            ->with(['foreignNational', 'exam.type'])
            ->chunkById(200, function($attempts){
                foreach($attempts as $attempt){
                    $foreignNational = $attempt->foreignNational;
                    $exam = $attempt->exam;
                    $this->csvWriter->writeRow([
                        ($foreignNational->passport_series ?? '') . ($foreignNational->passport_number ?? ''),
                        $exam->begin_time->format('d.m.Y'),
                        $attempt->is_passed ? 'Сдал' :  'Не сдал',
                        $exam->type->certificate_name
                    ]);
                }
            });
    }
}
