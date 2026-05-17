<?php

namespace App\Http\Controllers\Web\Exam;

use App\Domain\Attempt\Action\CreateAttemptAction;
use App\Domain\Center\CenterContext;
use App\Domain\Exam\Action\CancelExamAction;
use App\Domain\Exam\Action\CreateExamAction;
use App\Domain\Exam\Action\UpdateExamAction;
use App\Domain\Exam\Query\ExamShowQuery;
use App\Domain\Exam\Query\GetExamsQuery;
use App\Enums\EmployeeRole;
use App\Http\Requests\Exam\ExamIndexRequest;
use App\Http\Requests\Exam\VerifyCodeRequest;
use App\Http\Resources\Address\AddressResource;
use App\Http\Resources\Exam\ExamCalendarResource;
use App\Http\Resources\Exam\ExamIndexResource;
use App\Http\Resources\ExamType\ExamTypeResource;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Employee\EmployeeResource;
use App\Models\Address;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Requests\Exam\ExamPostRequest;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;


class ExamController
{
    public function index(ExamIndexRequest $request, GetExamsQuery $getExamQuery)
    {
        Gate::authorize('viewAny', Exam::class);
        $exams = $getExamQuery->execute($request->validated() ?? []);

        Inertia::flash('filters' , request()->all());

        $employee = $request->user();

        return Inertia::render('Exam/Exam', [
            'permissions' => [
                'create' => $employee->can('create', Exam::class),
                'flatTable' => $employee->hasRole(EmployeeRole::Director->value),
                'frdo' => $employee->can('frdo', Exam::class)
            ],
            'exams' => ExamIndexResource::collection($exams),
            
        ]);
        
    }

    public function store(ExamPostRequest $request, CreateExamAction $createExamAction)
    {       
        Gate::authorize('create', Exam::class);
        $createExamAction->execute($request->getDto(),$request->user());
        return response()->json();
    }

    public function createModalData(){
        Gate::authorize('create', Exam::class);
        $examiners=Employee::whereHas('roles', function(Builder $query){
                $query->where('name', EmployeeRole::Examiner->value);
            })
            ->active()
            ->get();
        return response()->json([
            'addresses' => AddressResource::collection(Address::where('is_active', true)->get()),
            'examTypes' => ExamTypeResource::collection(ExamType::all()),
            'examiners' => EmployeeResource::collection($examiners)
        ], 200);
    }

    public function show(
        Request $request, 
        Exam $exam, 
        ExamShowQuery $examShowQuery
    ){
        Gate::authorize('view', $exam);
        $employee = $request->user();
        $exam = $examShowQuery->execute($exam);
        return response()->json([
            'permissions' => [
                'documents'=> [
                    'codes' => $employee->can('examiner', $exam),
                    'protocol' => $employee->can('examiner', $exam),
                    'results' => $employee->can('examiner', $exam),
                    'list' => $employee->can('list', $exam)
                ],
                'actions' => [
                    'edit' => $employee->can('update', $exam),
                    'delete' => $employee->can('delete', $exam)
                ],
                'enrollments' => [
                    'view' => $employee->can('viewAny', Enrollment::class),
                    'statement' => $employee->hasAnyRole(EmployeeRole::Operator),
                    'payment' => $employee->hasAnyRole(EmployeeRole::Operator) || $employee->can('examiner', $exam)
                ]
                
            ],
            'exam' => new ExamResource($exam)
        ]);
    }

    public function update(
        ExamPostRequest $request, 
        Exam $exam, 
        UpdateExamAction $updateExam
    ){   
        Gate::authorize('update', $exam);
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

        return redirect()->route('attempts.show', ['attempt' => $attempt->id]);
    }

    public function destroy(
        Exam $exam, 
        CancelExamAction $cancelExam
    )
    {
        Gate::authorize('delete', $exam);
        
        request()->validate( [
            'cancelledReason' => ['required', 'string']
        ]);

        $cancelExam->execute($exam, request()->string('cancelledReason'));
        
        return response()->noContent();
    }

    public function schedule(Request $request){
        $request->validate([
            'dateFrom' => ['sometimes', 'date'],
            'dateTo' => ['sometimes', 'date']
        ]);

        $dateFrom = Carbon::parse($request->input('dateFrom'))
            ->copy()->startOfDay();
        $dateTo = Carbon::parse($request->input('dateTo'))
            ->copy()->endOfDay();
        $exams = Exam::query()
            ->forCenter(app(CenterContext::class)->id())
            ->visibleFor($request->user())
            ->with(['type', 'center'])
            ->where('begin_time', '>=', $dateFrom)
            ->where('begin_time', '<', $dateTo)     
            ->get();

        return Inertia::render('Schedule/Schedule',[
            'exams' => ExamCalendarResource::collection($exams),
            'permissions' => [
                'create' => $request->user()->can('create', Exam::class)
            ]
        ]);
    }

}