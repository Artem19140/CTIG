<?php

namespace App\Http\Controllers\Question;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Question\QuestionResource;

class QuestionController extends Controller
{

    public function index()
    {
        return QuestionResource::collection(Question::all());
    }

    public function store(Request $request)
    {       
        Question::create($request->all());
        return response()->json(['result' => 'ok']);
    }

    public function show(Question $question)
    {   
        $question->load(['answers']);
        return new QuestionResource($question);
    }

    public function update(Request $request, Question $question)
    {
        //
    }

    public function destroy(Question $question)
    {
        //
    }
}
