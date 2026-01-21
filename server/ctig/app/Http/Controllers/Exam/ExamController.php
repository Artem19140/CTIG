<?php

namespace App\Http\Controllers\Exam;

use App\Models\Exam;
use Illuminate\Http\Request;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Controllers\Controller;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ExamResource::collection(Exam::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Exam::create($request->all());
        return response()->json(["result" => "ok"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Exam $exam)
    {
       return new ExamResource($exam);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exam $exam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam)
    {
        //
    }
}
