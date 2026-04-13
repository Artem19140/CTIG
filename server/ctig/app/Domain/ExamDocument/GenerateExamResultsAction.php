<?php

namespace App\Domain\ExamDocument;

use App\Actions\Attempt\GetDetailedAttemptResultsAction;
use App\Domain\Attempt\Query\GetDetailedAttemptResultsQuery;
use App\Models\Exam;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerateExamResultsAction{
    public function __construct(
        protected GetDetailedAttemptResultsAction $getDetailedAttemptResults,
        protected ExamDocumentAvailable $examDocumentAvailable
    ){}
    public function execute(Exam $exam){
        $this->examDocumentAvailable->statement($exam);

        $exam->load([
            'foreignNationals.attempts' => function ($query) use ($exam) {
                $query->where('exam_id', $exam->id)
                        ->with('answers');
            }
        ]);

        $columns = []; 
        $data = [];   

        foreach ($exam->foreignNationals as $f) {
            foreach ($f->attempts as $a) {
                $blocks = GetDetailedAttemptResultsQuery::execute($a);
                foreach ($blocks as $block) {
                    $columns[$block['id']]['name'] = $block['name'];
                    $columns[$block['id']]['subblocks'] = [];

                    foreach ($block['subblocks'] as $sub) {
                        $columns[$block['id']]['subblocks'][$sub['id']] =  $sub['name'];
                    }
                }

                $row = [
                    'fio' => $f->full_name,
                    'passport' => $f->full_passport,
                    'started_at' => $a->started_at->format('H:i'),
                    'finished_at' => $a->finished_at->format('H:i'),
                    'attempt' => $a,
                    'results' => []
                ];

                foreach ($blocks as $block) {
                    foreach ($block['subblocks'] as $sub) {
                        $row['results'][$block['id']][$sub['id']] = $sub['answers_mark_sum'];
                    }
                }

                $data[] = $row;
            }
        }
        $pdf = Pdf::loadView('templates.pdf.exam.exam-results', [
            'exam' => $exam,
            'data' => $data,
            'columns' => $columns
        ])->setPaper('a4', 'landscape');

        return $pdf;
    }
}