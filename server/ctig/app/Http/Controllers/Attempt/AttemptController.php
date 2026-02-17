<?php

namespace App\Http\Controllers\Attempt;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExamAttempt\AttemptResource;
use App\Models\Exam;
use App\Models\Attempt;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    public function index(Request $request)
    {
        $studentId = $request->input('studentId');
        $examId = $request->input('examId');
        $examAttempts = Attempt::when($studentId, function (Builder $query, int $studentId){
            $query->where('student_id', $studentId);
        })
        ->when($examId, function (Builder $query, int $examId){
            $query->where('exam_id', $examId);
        })
        ->get();
        return AttemptResource::collection($examAttempts);
    }

    public function store(Request $request)
    {
        $student=$request->user();
        $hasCurrentExamAttempt = Attempt::where('student_id', $student->id)
                                    ->where('is_finished',  false)
                                    ->exists();
        if($hasCurrentExamAttempt){
            throw new BusinessException('Существует текущая попытка экзамена');
        }
        $exam=Exam::with('examType')->find($student->exam_id);
        $examDuration = $exam->examType->duration;
        DB::transaction(function () use($student, $exam, $examDuration) {
            Attempt::create([
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

    public function show(Attempt $attempt)
    {
        //
    }


    public function update(Request $request, Attempt $attempt)
    {
        //
    }

    public function destroy(Attempt $attempt)
    {
        //
    }
}
