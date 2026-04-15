<?php

namespace App\Http\Controllers\Web\Exam;

use App\Actions\Exam\Manage\UpdateExaminersAcion;
use App\Domain\Attempt\Action\CreateAttemptAction;
use App\Domain\Exam\Action\CancelExamAction;
use App\Domain\Exam\Action\CreateExamAction;
use App\Domain\Exam\Action\UpdateExamAction;
use App\Domain\Exam\Action\UpdateExaminersAction;
use App\Domain\Exam\Query\GetExamsQuery;
use App\Enums\UserRoles;
use App\Http\Requests\Exam\ExamIndexRequest;
use App\Http\Requests\Exam\VerifyCodeRequest;
use App\Http\Resources\Address\AddressResource;
use App\Http\Resources\Exam\ExamCalendarResource;
use App\Http\Resources\Exam\ExamIndexResource;
use App\Http\Resources\ExamType\ExamTypeResource;
use Carbon\Carbon;
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
use Inertia\Inertia;

class ExamController
{
    public function index(ExamIndexRequest $request, GetExamsQuery $getExamQuery)
    {
        $exams = $getExamQuery->execute($request->validated() ?? [], $request->user());
        return Inertia::render('Exam/Exam', [
            'exams' => ExamIndexResource::collection($exams),
            'filters' => request()->all()
        ]);
        
    }

    public function store(ExamPostRequest $request, CreateExamAction $createExamAction)
    {       
        $createExamAction->execute($request->getDto(),$request->user());
        return Inertia::flash('success', 'Экзамен создан')->back();
    }

    public function createModalData(){
        $examiners=User::whereHas('roles', function(Builder $query){
                        $query->where('name',UserRoles::Examiner->value);
                    })
                    ->get();
        return response()->json([
            'addresses' => AddressResource::collection(Address::where('is_active', true)->get()),
            'examTypes' => ExamTypeResource::collection(ExamType::all()),
            'examiners' => UserResource::collection($examiners) //Где роль тестеры
        ], 200);
    }

    public function show(Exam $exam)
    {
        $exam->load([
                    'examiners', 
                    'address',
                    'examType',
                    'enrollments' => ['foreignNational', 'exam', 'attempt'] //, 'attempt'
                    
                ]);
        
        $exam->loadCount('enrollments');
        return new ExamResource($exam);
    }

    public function update(ExamPostRequest $request, Exam $exam, UpdateExamAction $updateExam)
    {   
        $updateExam->execute($exam, $request->getDto(), $request->user());
        return Inertia::flash('success', 'Данные обновлены')->back();
    }

    public function partialUpdate(Request $request, Exam $exam, UpdateExaminersAction $updateExaminers)
    {   
        $request->validate([
            'examiners' => ['required', 'array','min:1'],
            'examiners.*' => ['required', 'integer', 'min:1', 'exists:users,id'],
            'comment' => ['nullable', 'string']
        ]);
        $updateExaminers->execute($request->input('examiners'), $exam, $request->user());
        return Inertia::flash('success', 'Данные обновлены')->back();
    }

    public function verifyCode(
                                VerifyCodeRequest $request,
                                CreateAttemptAction $createAttempt
                            ){
        $attempt = $createAttempt->execute($request->validated('code'));
        Auth::guard('foreignNationals')->login($attempt->foreignNational);
        $request->session()->regenerate();
        return redirect()->route('exam-attempts.before', ['attempt' => $attempt->id]);
    }

    public function destroy(Exam $exam , CancelExamAction $cancelExam)
    {
        request()->validate( [
            'cancelledReason' => ['required', 'string']
        ]);
        $cancelExam->execute($exam);
        Inertia::flash('success', 'Экзамен отменен');
        return back();
    }

    public function schedule(Request $request){
        $dateFrom = $request->input('dateFrom') ?? false;
        $dateTo = $request->input('dateTo') ?? false;
        $exams = Exam::with('examType')
                    ->when($dateFrom, function(Builder $query, $dateFrom) {
                        $begin= Carbon::parse($dateFrom)->startOfDay();
                        $query->where('begin_time', '>=', $begin);
                    })
                    ->when($dateTo, function(Builder $query, $dateTo) {
                        $end= Carbon::parse($dateTo)->endOfDay();
                        $query->where('begin_time','<=', $end);
                    })
                    ->when(!$dateFrom, function(Builder $query) {
                        $query->whereBetween('begin_time', [now()->startOfMonth(), now() ->endOfMonth()]);
                    })
                    ->get();
        return Inertia::render('Schedule/Schedule',[
            'exams' => ExamCalendarResource::collection($exams )
        ]);
    }
}