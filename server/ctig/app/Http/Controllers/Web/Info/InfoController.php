<?php

namespace App\Http\Controllers\Web\Info;

use App\Http\Resources\Exam\ExamCalendarResource;
use App\Models\Exam;
use Inertia\Inertia;

class InfoController
{
    public function index(){
        $exams = Exam::with('examType')
                ->before( now()->endOfMonth())
                ->after(now()->startOfMonth())
                ->get();
        return Inertia::render('Info/Info',[
            'exams' => ExamCalendarResource::collection($exams)
        ]);
    }
}
