<?php

namespace App\Http\Controllers\Web\Exam;

use App\Actions\Attempt\StartExamSessionAction;
use App\Actions\Exam\Manage\CancelExamAction;
use App\Actions\Exam\GetExamListAction;
use App\Actions\Exam\VerifyExamCodeAction;
use App\Enums\UserRoles;
use App\Exceptions\BusinessException;
use App\Http\Requests\Exam\ExamIndexRequest;
use App\Http\Resources\Address\AddressResource;
use App\Http\Resources\ExamType\ExamTypeResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User\UserResource;
use App\Models\Address;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Requests\Exam\ExamPostRequest;
use App\Actions\Exam\Manage\CreateExamAction;
use Inertia\Inertia;

class ExamController
{
    public function index(ExamIndexRequest $request, GetExamListAction $getExamList)
    {
        $exams = $getExamList->execute($request->validated() ?? []);
        return Inertia::render('Exam/Exam', [
            'exams' => ExamResource::collection($exams),
            'filters' => request()->all()
        ]);
        
    }

    public function store(ExamPostRequest $request, CreateExamAction $createExamAction)
    {       
        $createExamAction->handle($request->getDto(),$request->user());
        return Inertia::flash('success', 'Экзамен создан')->back();
    }

    public function createModalData(){
        $examiners=User::whereHas('roles', function(Builder $query){
                        $query->where('name',UserRoles::Examiner->value);
                    })
                    ->get();
        return response()->json([
            'addresses' => AddressResource::collection(Address::all()),
            'examTypes' => ExamTypeResource::collection(ExamType::all()),
            'examiners' => UserResource::collection($examiners) //Где роль тестеры
        ], 200);
    }

    public function show(Exam $exam)
    {
        $exam->load(['foreignNationals.attempts' => function (HasMany $query) use($exam){
                        $query->where('exam_id', $exam->id);
                    }, 
                    'examiners', 
                    'address',
                    'examType'
                ]);
        $exam->loadCount('foreignNationals');
        return new ExamResource($exam);
    }

    public function update(ExamPostRequest $request, Exam $exam)
    {   
        if($exam->foreignNationals()->count() > 0){
            throw new BusinessException('Нельзя редактировать экзамен, на который уже записаны ИГ');
        }
    }

    public function partialUpdate(Request $request, Exam $exam)
    {   
        $request->validate([
            'examiners' => ['required', 'array'],
            'examiners.*' => ['required', 'integer', 'exists:users,id'],
        ]);
        return Inertia::flash('success', 'Данные обновлены')->back();
    }

    public function verifyCode(
                                Request $request,
                                VerifyExamCodeAction $verifyCode,
                                StartExamSessionAction  $startExamSession
                            ){
        $request->validate([
            'code' => ['required', 'string', 'size:6']
        ]);
        $foreignNational =  $verifyCode->execute($request->input('code'));
        Auth::guard('foreignNationals')->login($foreignNational);
        $request->session()->regenerate();
        $attempt = $startExamSession->execute($foreignNational);
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
            'exams' => ExamResource::collection($exams )
        ]);
    }
}