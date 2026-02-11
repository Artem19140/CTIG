<?php

namespace App\Http\Controllers\ExamCode;

use App\Exceptions\BusinessException;
use App\Models\ExamAttempt;
use App\Models\ExamCode;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Actions\Exam\CreateStudentsCodesForExamAction;

class ExamCodeController extends Controller
{
    public function index()
    {
        // после использования - сразу удаляй из бд, код одноразовый
    }

    public function store(Exam $exam, CreateStudentsCodesForExamAction $createStudentsCodesForExam)
    {
        //$studentsWithCodes = $createStudentsCodesForExam->execute($exam);
        return $createStudentsCodesForExam->execute($exam);
        //return $this->created($studentsWithCodes);
    }

    public function show(ExamCode $examCode)
    {
        //
    }

    public function update(Request $request, ExamCode $examCode)
    {
        //
    }

    public function destroy(ExamCode $examCode)
    {
        //
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
        // $student->create()
        // ExamAttempt::create([

        // ]);
        echo $student->surname;
    }
}
