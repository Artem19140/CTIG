<?php

namespace App\Http\Controllers\Web\Attempt;

use App\Domain\AttemptAnswer\Action\HandleAttemptAnswerAction;
use App\Domain\AttemptAnswer\Action\RateAttemptAnswerAction;
use App\Http\Requests\AttemptAnswer\AttemptAnswerRequest;
use App\Http\Resources\AttemptAnswer\AttemptAnswerResource;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\Exam;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AttemptAnswerController
{
    public function index(Request $request)
    {
        $answers = AttemptAnswer::where('attempt_id',$request->input('attemptId'))
            ->get();
        return AttemptAnswerResource::collection($answers);
    }

    public function update(
        AttemptAnswerRequest $request,
        Attempt $attempt, 
        AttemptAnswer $attemptAnswer,
        HandleAttemptAnswerAction $handleAttemptAnswerAction
    ){
        $this->authorize($attempt, $attemptAnswer);
        $answer = $request->input('answer');
        $savedAnswer = DB::transaction(function()use($answer, $attempt,$attemptAnswer, $handleAttemptAnswerAction){
            $answer = $handleAttemptAnswerAction->execute($answer, $attemptAnswer);
            $attempt->last_activity_at = Carbon::now();
            $attempt->save();
            return $answer;
        });
        return new AttemptAnswerResource($savedAnswer);
    }

    public function rate(
        Request $request,
        AttemptAnswer $attemptAnswer,
        RateAttemptAnswerAction $rateAttemptAnswerAction
    ){
        $request->validate([
            'mark' => ['required', 'integer', 'min:0']
        ]);
        Gate::authorize('examiner', $attemptAnswer->attempt->exam);

        $attemptAnswer = $rateAttemptAnswerAction->execute($attemptAnswer, $request->input('mark'));
        
        return response()->json(['attemptAnswer' => new AttemptAnswerResource($attemptAnswer)]);
    }

    public function audioPlayed(
        Attempt $attempt,
        AttemptAnswer $attemptAnswer,
    ){
        $this->authorize($attempt, $attemptAnswer);

        $attemptAnswer->audio_played = true;
        $attemptAnswer->save();
        return response()->noContent();
    }

    protected function authorize(Attempt $attempt, AttemptAnswer $attemptAnswer){
        abort_if($attempt->id !== $attemptAnswer->attempt_id, 403);
    }
}
