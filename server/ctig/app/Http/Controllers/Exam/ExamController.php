<?php

namespace App\Http\Controllers\Exam;

use App\Models\Exam;
use Illuminate\Http\Request;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Exam\ExamRequest;

class ExamController extends Controller
{
    
    public function index()
    {
        return ExamResource::collection(Exam::all());
    }

    public function store(ExamRequest $request)
    {       
        //User::findOrFail(request()->input(''));
        //Проверка, что в это время нет экзаменов
        Exam::create($request->all());
        return response()->json(["result" => "ok"]);
    }

    public function show(int $examId): ExamResource
    {
        $exam = Exam::findOrFail($examId);
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
