<?php

namespace App\Http\Controllers\Web\Exam;

use App\Actions\Exam\CancelExamAction;
use App\Actions\Exam\GetExamListAction;
use App\Actions\Exam\VerifyExamCodeAction;
use App\Exceptions\BusinessException;
use App\Http\Requests\Exam\ExamIndexRequest;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Requests\Exam\ExamPostRequest;
use App\Actions\Exam\CreateExamAction;
use Inertia\Inertia;

class ExamController
{
    public function index(ExamIndexRequest $request, GetExamListAction $getExamList)
    {
        $exams = $getExamList->execute($request->validated() ?? []);
        return Inertia::render('Exam/Exam', [
            'exams' =>  ExamResource::collection($exams)->resolve()
        ]);
    }

    // public function store(ExamPostRequest $request, CreateExamAction $createExamAction)
    // {       
    //     $exam = $createExamAction->handle($request->getDto(),$request->user()->id);
    //     return new ExamResource($exam)
    //                 ->response()
    //                 ->setStatusCode(201);;
    // }

    public function show(Exam $exam)
    {
        return Inertia::render('Exam');
        // $exam->load(['students', 'testers', 'address', 'examType']);
        // return new ExamResource($exam);
    }

    public function update(ExamPostRequest $request, Exam $exam)
    {
        
    }

    public function verifyCode(Request $request, VerifyExamCodeAction $verifyExamCode){
        $request->validate([
            'code' => ['required', 'string']
        ]);
        $verifyExamCode->execute($request->input('code'));
        return Inertia::render('Exam');
        return $this->created($token);
    }

    // public function destroy(Exam $exam , CancelExamAction $cancelExam)
    // {
    //     request()->validate( [
    //         'cancelledReason' => ['required', 'string']
    //     ]);
    //     $cancelExam->execute($exam);
    //     return response()->noContent();
    // }

    // public function state(Exam $exam){
    //     if($exam->isPassed()){
    //         throw new BusinessException('Экзамен уже прошел');
    //     }
    //     $exam->load([
    //         'students.attempts' => fn ($query) => $query->where('exam_id', $exam->id)->with('violations')
    //     ]);
        
    //     return new ExamResource($exam);
    // }
}