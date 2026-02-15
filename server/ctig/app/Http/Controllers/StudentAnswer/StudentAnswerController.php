<?php

namespace App\Http\Controllers\StudentAnswer;

use App\Exceptions\BusinessException;
use App\Models\StudentAnswer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentAnswerController extends Controller
{
    public function index(Request $request)
    {
        $answers = StudentAnswer::where('exam_attempt_id',$request->input('examAttemptId'))
                    ->get();
        //return new StudentAnswerResource
    }

    public function store(Request $request)
    {

    }

    public function show(StudentAnswer $studentAnswer)
    {
        //
    }

    public function update(Request $request, StudentAnswer $studentAnswer)
    {
        $studentAnswer->load('attempt');
        $examAttempt = $studentAnswer->attempt;
        if($examAttempt->expired_at < Carbon::now()){
            //$request->user()->tokens()->delete();
            //кыш мыш токен из ls??
            throw new BusinessException('Время экзамена вышло');
        }
        $studentAnswer->student_answer = $request->validate([
            'studentAnswer' => ['nullable', 'string']
        ]);
        return $this->noContent();
    }

    public function destroy(StudentAnswer $studentAnswer)
    {
        //
    }
}
