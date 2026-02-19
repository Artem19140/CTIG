<?php

namespace App\Http\Controllers\ExamCode;

use App\Exceptions\BusinessException;
use App\Models\Exam;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Actions\Exam\CreateStudentsCodesForExamAction;

class ExamCodeController extends Controller
{
    public function store(Exam $exam, CreateStudentsCodesForExamAction $createStudentsCodesForExam)
    {
        return $createStudentsCodesForExam->execute($exam);
    }
  
    public function verify(Request $request){
        $student = Student::where('exam_code', $request->input('examCode'))
                        ->first();
        if(!$student){
            throw new BusinessException('Код не найден');
        }

        if($student->exam_code_expired_at < Carbon::now()){
            throw new BusinessException('Истек срок действия кода');
        }

        $exam = Exam::find($student->exam_id);

        if($exam->isPassed()){
            throw new BusinessException('Экзмен уже прошел');
        }

        if($exam->begin_time >= Carbon::now()){
            throw new BusinessException('Экзмен еще не начался');
        }
        $token = DB::transaction(function () use($student){
            $student->exam_code = null;
            $student->exam_code_expired_at = null;
            $student->save();
            return $student->createToken(
            'pre-exam-token',
            ['exam:prepare'],
            Carbon::now()->addMinutes(10)
            )->plainTextToken;
        });
        
        return $this->created($token);
    }
}
