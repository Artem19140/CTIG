<?php

namespace App\Http\Controllers\Exam;

use App\Enums\ExamStatus;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\ExamType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Exam\ExamPostRequest;
use App\Actions\Exam\CreateExamAction;

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
                $query->where('exam_date', '>=',$dateFrom);
            })
            ->when($dateTo, function (Builder $query, string $dateTo){
                $query->where('exam_date', '<=',$dateTo);
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
        $exam = $createExamAction->handle($request->getDto(),$request->user()->id);
        return $this->created(new ExamResource($exam));
    }

    public function show(Exam $exam): ExamResource
    {
        //$exam->load('students'); //в параметр добавь что-нибудь, чтобы со списком и без получать
        return new ExamResource($exam);
    }

    public function update(Request $request, Exam $exam)
    {
        //
    }

    public function destroy(Exam $exam)
    {
        //
    }
}