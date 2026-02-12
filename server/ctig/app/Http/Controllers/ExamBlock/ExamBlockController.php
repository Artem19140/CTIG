<?php

namespace App\Http\Controllers\ExamBlock;

use App\Exceptions\BusinessException;
use App\Exceptions\EntityNotFoundExсeption;
use App\Models\ExamBlock;
use App\Http\Requests\ExamBlock\StoreExamBlockRequest;
use App\Http\Requests\ExamBlock\UpdateExamBlockRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExamBlock\ExamBlockResource;
use App\Models\ExamType;

class ExamBlockController extends Controller
{

    public function index()
    {
        request()->validate([
            'examTypeId' => ['required', 'integer'],
        ]);
        $examType = ExamType::find(request()->input('examTypeId'));
        if(!$examType){
             throw new EntityNotFoundExсeption('Блок экзамена');
            throw new BusinessException('Такого блока экзамена не сущестует');
        }
        $examBlocks = ExamBlock::where('exam_type_id', $examType->id)->get();
        return ExamBlockResource::collection($examBlocks);
    }

    public function store(StoreExamBlockRequest $request)
    {
        //Отслеживание порядка массив из количеста  экементов = порядку
        $examType = ExamType::find(request()->input('examTypeId'));
        if(!$examType){
            throw new EntityNotFoundExсeption('Блок экзамена');

        }

        $examBlock = ExamBlock::create([
            'name' =>request()->input('name'),
            'min_mark' =>request()->input('minMark'),
            'exam_type_id' =>request()->input('examTypeId'),
            'creator_id' =>request()->user()->id,
            'order' =>request()->input('order')
        ]);
        return $this->created(new ExamBlockResource($examBlock));
    }

    public function show(ExamBlock $examBlock)
    {
        return new ExamBlockResource($examBlock);
    }

    public function tests(ExamBlock $examBlock){
        $examBlock->load('tasks.answers');
        return new ExamBlockResource($examBlock);
    }

    public function update(UpdateExamBlockRequest $request, ExamBlock $examBlock)
    {
        //
    }

    public function destroy(ExamBlock $examBlock)
    {
        //
    }
}
