<?php

namespace App\Http\Controllers\Api\ExamCode;

use App\Exceptions\BusinessException;
use App\Models\Exam;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Models\Student;
use App\Actions\Exam\CreateCodesAction;
use App\Enums\TokenAbilities;

class ExamCodeController extends Controller
{
    public function store(Exam $exam, CreateCodesAction $createCodes)
    {
        return $createCodes->execute($exam);
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
            TokenAbilities::ExamPrepare->value,
            [TokenAbilities::ExamPrepare->value],
            Carbon::now()->addMinutes(10)
            )->plainTextToken;
        });
        
        return $this->created($token);
    }
}
