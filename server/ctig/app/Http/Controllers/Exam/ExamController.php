<?php

namespace App\Http\Controllers\Exam;

use App\Exceptions\BusinessException;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Exam\ExamPostRequest;
use App\Actions\Exam\CreateExamAction;
use Illuminate\Support\Facades\Gate;

class ExamController extends Controller
{
    
    public function index(Request $request)
    {
        //Валидация
        $examTypeId = $request->input('examTypeId');
        $dateFrom = $request->input('dateFrom');
        $dateTo = $request->input('dateTo');
        $addressId = $request->input('addressId');
        $examStatus = $request->input('examStatus');
        $exams = Exam::when($examTypeId, function (Builder $query, int $examTypeId) {
                $query->where('exam_type_id', $examTypeId);
            })
            ->when($dateFrom, function (Builder $query, string $dateFrom){
                $query->where('date', '>=',$dateFrom);
            })
            ->when($dateTo, function (Builder $query, string $dateTo){
                $query->where('date', '<=',$dateTo);
            })
            ->when($addressId, function (Builder $query, string $addressId){
                $query->where('address_id',$addressId);
            })
            ->when($examStatus, function (Builder $query, string $examStatus){
                $query->where('status',$examStatus);
            })
            ->simplePaginate(15);
        return ExamResource::collection($exams);
    }

    public function store(ExamPostRequest $request, CreateExamAction $createExamAction)
    {       
        // if($request->user()->cannot('create', Exam::class)){
        //     abort(403, 'Нет доступа');
        // }
        //Gate::authorize('create', Exam::class);
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

    public function destroy(Exam $exam)
    {
        request()->validate( [
            'cancelledReason' => ['required', 'string']
        ]);

        if($exam->isPassed() || $exam->isGoing()){
            throw new BusinessException('Экзамен уже прошел или идет');
        }

        if($exam->is_cancelled){
            throw new BusinessException('Экзамен уже отменен');
        }
        
        $exam->cancelled_reason = request()->input('cancelledReason');
        $exam->is_cancelled = true;
        $exam->save();
        return $this->noContent();
    }

    public function state(Exam $exam){
        if($exam->isPassed()){
            throw new BusinessException('Экзамен уже прошел');
        }
        $exam->load([
                'students.attempts' => fn ($query) => $query->where('exam_id', $exam->id)->with('violations')
            ]);
        
        return new ExamResource($exam);
    }
}