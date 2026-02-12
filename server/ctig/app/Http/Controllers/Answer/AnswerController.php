<?php

namespace App\Http\Controllers\Answer;


use App\Exceptions\EntityNotFoundExсeption;
use App\Http\Controllers\Controller;
use App\Http\Requests\Answer\AnswerRequest;
use App\Models\Answer;
use App\Models\TaskVariant;
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

    public function store(AnswerRequest $request)
    {
        $taskVariant = TaskVariant::find($request->input('taskVariantId'));
        if(!$taskVariant){
            throw new EntityNotFoundExсeption('Вариант задания');
        }
        $answer = Answer::create([
            'contain' => $request->input('contain'),
            'task_variant_id' => $request->input('taskVariantId'),
            'mark' => $request->input('mark'),
            'is_correct' =>$request->input(key: 'isCorrect'),
            'creator_id' => $request->user()->id
        ]);
        return $this->created(new AnswerResource($answer));
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
