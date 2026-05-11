<?php

namespace App\Http\Controllers\Web\Exam;

use App\Domain\Attempt\Action\CreateAttemptAction;
use App\Domain\Exam\Action\CancelExamAction;
use App\Domain\Exam\Action\CreateExamAction;
use App\Domain\Exam\Action\UpdateExamAction;
use App\Domain\Exam\Query\ExamShowQuery;
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
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ExamController
{
    public function index(ExamIndexRequest $request, GetExamsQuery $getExamQuery)
    {
        $exams = $getExamQuery->execute($request->validated() ?? []);
        Inertia::flash('filters' , request()->all());
        return Inertia::render('Exam/Exam', [
            'exams' => ExamIndexResource::collection($exams),
        ]);
        
    }

    public function store(ExamPostRequest $request, CreateExamAction $createExamAction)
    {       
        $createExamAction->execute($request->getDto(),$request->user());
        return response()->json();
    }

    public function createModalData(Request $request){
        $examiners=User::whereHas('roles', function(Builder $query){
                $query->where('name',UserRoles::Examiner->value);
            })
            ->active()
            ->get();
        return response()->json([
            'addresses' => AddressResource::collection(Address::where('is_active', true)->where('center_id', $request->user()->center_id)->get()),
            'examTypes' => ExamTypeResource::collection(ExamType::all()),
            'examiners' => UserResource::collection($examiners)
        ], 200);
    }

    public function show(Exam $exam, ExamShowQuery $examShowQuery){
        Gate::authorize('view', $exam);
        $exam = $examShowQuery->execute($exam);
        return new ExamResource($exam);
    }

    public function update(
        ExamPostRequest $request, 
        Exam $exam, 
        UpdateExamAction $updateExam
    ){   
        $updateExam->execute(
            $exam, 
            $request->getDto()
        );
        return response()->json(['exam' => new ExamResource($exam)]);
    }

    public function verifyCode(
        VerifyCodeRequest $request,
        CreateAttemptAction $createAttempt
    ){
        $attempt = $createAttempt->execute($request->validated('code'));

        Auth::guard('foreignNationals')->login($attempt->foreignNational);

        $request->session()->regenerate();

        return redirect()->route('attempts.preparing', ['attempt' => $attempt->id]);
    }

    public function destroy(
        Exam $exam , 
        CancelExamAction $cancelExam
    )
    {
        request()->validate( [
            'cancelledReason' => ['required', 'string']
        ]);
        $cancelExam->execute($exam);
        
        return response()->noContent();
    }

    public function schedule(Request $request){

        $dateFrom = Carbon::parse($request->input('dateFrom'))->startOfDay();
        $dateTo = Carbon::parse($request->input('dateTo'))->endOfDay();

        $exams = Exam::with(['type', 'center'])
            ->whereBeginTimeMore( $dateFrom)
            ->whereBeginTimeLess($dateTo)
            ->get();
        
        return Inertia::render('Schedule/Schedule',[
            'exams' => ExamCalendarResource::collection($exams )
        ]);
    }

}