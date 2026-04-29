<?php

namespace App\Domain\ExamDocument;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;


class ExamProtocolGenerator{
    public function execute(Exam $exam, User $user){

        $bannedAttempts = $this->getBannedAttempts($exam);
        $beginTimeReal = $this->getBeginTimeReal($exam);
        $endTimeReal = $this->getEndTimeReal($exam);

        $pdf = Pdf::loadView('templates.exam-protocol', [
            'exam' => $exam,
            'center' => $user->center, 
            'bannedAttempts' => $bannedAttempts,
            'beginTimeReal' => $beginTimeReal,
            'endTimeReal' => $endTimeReal
        ]);

        return $pdf->stream("codes.pdf");
    }

    protected function getBannedAttempts(Exam $exam):Collection{
        return Attempt::with('foreignNational')
            ->where('exam_id', $exam->id)
            ->where('status', AttemptStatus::Banned)->get();
    }

    protected function getBeginTimeReal(Exam $exam):Carbon | null{
        $min = $exam->attempts()
            ->min('started_at');

        return $min ? Carbon::parse($min, 'UTC') : null;
    }

    protected function getEndTimeReal(Exam $exam):Carbon | null{
        $max = Carbon::parse(
            $exam->attempts()
                ->max('finished_at')
        );
        return $max;
    }
}