<?php

namespace App\Http\Controllers\ExamBlock;

use App\Models\ExamBlock;
use App\Http\Requests\ExamBlock\StoreExamBlockRequest;
use App\Http\Requests\ExamBlock\UpdateExamBlockRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExamBlock\ExamBlockResource;

class ExamBlockController extends Controller
{

    public function index()
    {
        return ExamBlockResource::collection(ExamBlock::all());
    }

    public function store(StoreExamBlockRequest $request)
    {
        ExamBlock::create($request->all());
        return response()->json(["result" => "ok"]);
    }

    public function show(ExamBlock $examBlock)
    {
        return new ExamBlockResource($examBlock);
    }

    public function tests(ExamBlock $examBlock){
        $examBlock->load('questions.answers');
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
