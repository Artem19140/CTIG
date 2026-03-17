<?php

namespace App\Http\Controllers\Web\Exam;

use App\Actions\Attempt\StartExamSessionAction;
use App\Actions\Exam\CancelExamAction;
use App\Actions\Exam\CreateCodesAction;
use App\Actions\Exam\GetAvailableExamsAction;
use App\Actions\Exam\GetExamListAction;
use App\Actions\Exam\VerifyExamCodeAction;
use App\Http\Requests\Exam\ExamIndexRequest;
use App\Http\Resources\Address\AddressResource;
use App\Http\Resources\ExamType\ExamTypeResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
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
            'exams' => ExamResource::collection($exams)
        ]);
        
    }

    public function store(ExamPostRequest $request, CreateExamAction $createExamAction)
    {       
        $createExamAction->handle($request->getDto(),$request->user());
        return back()
            ->with('success', 'Экзамен создан');

    }

    public function createModalData(){
        return response()->json([
            'addresses' => AddressResource::collection(Address::all()),
            'examTypes' => ExamTypeResource::collection(ExamType::all()),
            'testers' => UserResource::collection(User::all()) //Где роль тестеры
        ], 200);
    }

    public function show(Exam $exam)
    {
        $exam->load(['students', 'testers', 'address', 'examType']);
        return new ExamResource($exam);
    }

    public function update(ExamPostRequest $request, Exam $exam)
    {
        
    }

    public function verifyCode(
                                Request $request,
                                VerifyExamCodeAction $verifyCode,
                                StartExamSessionAction  $startExamSession
                            ){
        $request->validate([
            'code' => ['required', 'string']
        ]);
        $student =  $verifyCode->execute($request->input('code'));
        Auth::guard('students')->login($student);
        $request->session()->regenerate();
        $attempt = $startExamSession->execute($student);
        return redirect()->route('exam-attempts.before', ['attempt' => $attempt->id]);
    }

    public function destroy(Exam $exam , CancelExamAction $cancelExam)
    {
        request()->validate( [
            'cancelledReason' => ['required', 'string']
        ]);
        $cancelExam->execute($exam);
        return back()->with('success', 'Экзамен отменен');
    }

    public function formCodes(Exam $exam, CreateCodesAction $createCodes)
    {
        return $createCodes->execute($exam);
    }

    public function available(Request $request, GetAvailableExamsAction $getAvailableExams){
        $request->validate([
            'examTypeId' => ['nullable', 'integer', 'min:1']
        ]);
        $exams = $getAvailableExams->execute($request->input('examTypeId'));
        return ExamResource::collection($exams);
    }        
    
    public function schedule(Request $request){
        $dateFrom = $request->input('dateFrom') ?? false;
        $dateTo = $request->input('dateTo') ?? false;
        $exams = Exam::with('examType')
                    ->when($dateFrom, function(Builder $query, $dateFrom) {
                        $query->where('begin_time', '>=', $dateFrom);
                    })
                    ->when($dateTo, function(Builder $query, $dateTo) {
                        $query->where('begin_time','<=', $dateTo);
                    })
                    ->when(!$dateFrom, function(Builder $query) {
                        $query->whereBetween('begin_time', [now()->startOfWeek(), now() ->endOfWeek()]);
                    })
                    ->get();
        return Inertia::render('Schedule/Schedule',[
            'exams' => ExamResource::collection($exams )
        ]);
    }

}