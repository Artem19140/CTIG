<?php

namespace App\Http\Controllers\Answer;


use App\Http\Controllers\Controller;
use App\Models\Answer;
use Illuminate\Http\Request;
use App\Http\Resources\Answer\AnswerResource;

class AnswerController extends Controller
{
    public function index()
    {
        return AnswerResource::collection(Answer::all());
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        Answer::create($request->all());
        return response()->json(['result' => 'ok']);
    }

    public function show(Answer $answer)
    {
        return new AnswerResource($answer);
    }

    public function edit(Answer $answer)
    {
        //
    }

    public function update(Request $request, Answer $answer)
    {
        //
    }

    public function destroy(Answer $answer)
    {
        //
    }
}
