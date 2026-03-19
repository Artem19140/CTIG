<?php

namespace App\Http\Controllers\Api\Exam;

use App\Actions\Exam\CancelExamAction;
use App\Actions\Exam\GetExamListAction;
use App\Actions\Exam\VerifyExamCodeAction;
use App\Exceptions\BusinessException;
use App\Http\Requests\Exam\ExamIndexRequest;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Exam\ExamPostRequest;
use App\Actions\Exam\CreateExamAction;
use Illuminate\Support\Facades\Gate;

class ExamController extends Controller
{
    
    public function index(ExamIndexRequest $request, GetExamListAction $getExamList)
    {
        //Валидация
        $exams = $getExamList->execute($request->validated() ?? []);
        return ExamResource::collection($exams);
    }

    public function store(ExamPostRequest $request, CreateExamAction $createExamAction)
    {       
        $exam = $createExamAction->handle($request->getDto(),$request->user()->id);
        return $this->created(new ExamResource($exam));
    }

    public function show(Exam $exam): ExamResource
    {
        $exam->load(['students', 'testers', 'address', 'examType']);
        return new ExamResource($exam);
    }

    public function update(ExamPostRequest $request, Exam $exam)
    {
        
    }

    public function verifyCode(Request $request, VerifyExamCodeAction $verifyExamCode){
        $token = $verifyExamCode->execute($request->input('examCode'));
        return $this->created($token);
    }

    public function destroy(Exam $exam , CancelExamAction $cancelExam)
    {
        request()->validate( [
            'cancelledReason' => ['required', 'string']
        ]);
        $cancelExam->execute($exam);
        return $this->noContent();
    }

    public function state(Exam $exam){
        if($exam->isCompleted()){
            throw new BusinessException('Экзамен уже прошел');
        }
        $exam->load([
            'students.attempts' => fn ($query) => $query->where('exam_id', $exam->id)->with('violations')
        ]);
        
        return new ExamResource($exam);
    }
}