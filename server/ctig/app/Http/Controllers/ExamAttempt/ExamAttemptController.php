<?php

namespace App\Http\Controllers\ExamAttempt;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ExamAttemptController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $student=$request->user();
        $hasCurrentExamAttempt = ExamAttempt::where('student_id', $student->id)
                                    ->where('is_finished',  false)
                                    ->exists();
        if($hasCurrentExamAttempt){
            throw new BusinessException('Существует текущая попытка экзамена');
        }
        $exam=Exam::with('examType')->find($student->exam_id);
        $examDuration = $exam->examType->duration;
        DB::transaction(function () use($student, $exam, $examDuration) {
            ExamAttempt::create([
                'student_id' => $student->id,
                'exam_id' => $exam->id,
                'expired_at' => Carbon::now()->addMinutes($examDuration),
                'is_banned' => false,
                'finished_at' => now()
            ]);

            $student->tokens()->delete();
            $student->createToken(
                'exam-token',
                ['exam:access'],
                Carbon::now()->addMinutes($examDuration + 1)
            );
        });
        return $this->created();
    }

    public function show(ExamAttempt $examAttempt)
    {
        //
    }


    public function update(Request $request, ExamAttempt $examAttempt)
    {
        //
    }

    public function destroy(ExamAttempt $examAttempt)
    {
        //
    }
}
