<?php

namespace App\Http\Controllers\ExamType;

use App\Models\ExamType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExamType\ExamTypeResource;

class ExamTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ExamTypeResource::collection(ExamType::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ExamType $examType)
    {
        //$examType->load('questions.answers');
        return new ExamTypeResource($examType);
    }

    public function blocks(ExamType $examType){
        $examType->load('blocks');
        return new ExamTypeResource($examType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExamType $examType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamType $examType)
    {
        //
    }
}
