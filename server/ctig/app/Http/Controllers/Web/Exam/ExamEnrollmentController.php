<?php

namespace App\Http\Controllers\Web\Exam;

use App\Domain\Exam\Query\GetAvailableExamsQuery;
use App\Http\Requests\Enrollment\EnrollmentAvailableRequest;

class ExamEnrollmentController
{
    public function available(
        EnrollmentAvailableRequest $request, 
        GetAvailableExamsQuery $getAvailableExamsQuery
    ){
        
        $exams = $getAvailableExamsQuery->execute(
            $request->validated('examTypeId'), 
            $request->validated('foreignNationalId')
        );

        return $exams->map(function ($exam) {
            return [
                'id' => $exam->id,
                'beginTime' => $exam->begin_time_local->format('H:i d.m.Y'),
            ];
        });
    } 
}
