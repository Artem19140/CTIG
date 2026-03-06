<?php

namespace App\Http\Controllers\Web\Exam;

use App\Actions\Exam\CancelExamAction;
use App\Actions\Exam\GetExamListAction;
use App\Actions\Exam\VerifyExamCodeAction;
use App\Http\Requests\Exam\ExamIndexRequest;
use App\Http\Resources\Address\AddressResource;
use App\Http\Resources\ExamType\ExamTypeResource;
use App\Http\Resources\User\UserResource;
use App\Models\Address;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\User;
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
            'exams' => ExamResource::collection($exams)->resolve(),
            'create' => false
        ]);
    }

    public function store(ExamPostRequest $request, CreateExamAction $createExamAction)
    {       
        $createExamAction->handle($request->getDto(),$request->user()->id);
        return redirect()
            ->route('exams.index')
            ->with('success', 'Экзамен создан');

    }

    public function createModalData(){
        return response()->json([
            'addresses' => AddressResource::collection(Address::all())->resolve(),
            'examTypes' => ExamTypeResource::collection(ExamType::all())->resolve(),
            'testers' => UserResource::collection(User::all())->resolve() //Где роль тестеры
        ], 200);
    }

    public function show(Exam $exam)
    {
        //return Inertia::render('Exam');
        $exam->load(['students', 'testers', 'address', 'examType']);
        return new ExamResource($exam);
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