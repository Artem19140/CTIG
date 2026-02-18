<?php

namespace App\Http\Controllers\ExamType;

use App\Models\ExamType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExamType\ExamTypeResource;

class ExamTypeController extends Controller
{
    public function index()
    {
        return ExamTypeResource::collection(ExamType::all());
    }

    public function store(Request $request)
    {
        //
    }

    public function show(ExamType $examType)
    {
        $examType->load('blocks.subblocks.tasks.variants.answers');
        return new ExamTypeResource($examType);
    }

    public function blocks(ExamType $examType){
        //$examType->load('blocks');
        return new ExamTypeResource($examType);
    }

    public function update(Request $request, ExamType $examType)
    {
        //
    }

    public function destroy(ExamType $examType)
    {
        //
    }
}
