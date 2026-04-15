<?php

namespace App\Http\Controllers\Web\Attempt;

use App\Domain\Attempt\Action\FinishAttemptAction;
use App\Domain\AttemptAnswer\Action\HandleAttemptAnswerAction;
use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Http\Requests\AttemptAnswer\AttemptAnswerRequest;
use App\Http\Resources\AttemptAnswer\AttemptAnswerResource;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class AttemptAnswerController extends Controller
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
                            HandleAttemptAnswerAction $handleAttemptAnswer
                        )
    {
        $foreignNational = $request->user();
        if($attempt->foreign_national_id !== $foreignNational->id){
            abort(403);
        }
        $answer = $request->input('answer');
        $savedAnswer = DB::transaction(function()use($answer, $attempt,$attemptAnswer, $handleAttemptAnswer){
            $answer = $handleAttemptAnswer->execute($answer, $attempt, $attemptAnswer);
            $attempt->last_activity_at = Carbon::now($attempt->center->time_zone);
            $attempt->save();
            return $answer;
        });
        return new AttemptAnswerResource($savedAnswer);
    }

    public function rate(
                            Request $request,
                            AttemptAnswer $attemptAnswer,
                            FinishAttemptAction $finalizeAttemptChecking
                        ){
        //лучше массивом объектов обновлять, чтобы за раз все выставить и сохранить на фронте
        // $exam = Exam::findOrFail($attempt->exam_id);
        // Gate::authorize('exam-manage-access', $exam);
        $request->validate([
            'mark' => ['required', 'integer', 'min:0'] //нужно проверить, что у самого задания оценка не меньше марк
        ]);

        if($attemptAnswer->is_checked){
            throw new BusinessException('Задание уже проверено и оценено');
        }

        $attemptAnswer->load(['taskVariant.task', 'attempt']);
        $attempt = $attemptAnswer->attempt;

        if($attempt->status !== AttemptStatus::Finished){
            throw new BusinessException('Попытка незавершена или аннулирована');
        }

        $task = $attemptAnswer->taskVariant->task;

        if($task->type->autoCheck()){
            throw new BusinessException('Задание проверяется автоматически');
        }

        if($task->mark < $request->input('mark')){
            throw new BusinessException('Выставленный балл больше, чем максимально возможный за задание');
        }

        DB::transaction(function () use($attemptAnswer, $attempt,$request, $finalizeAttemptChecking) {
            $attemptAnswer->mark = $request->input('mark');
            $attemptAnswer->is_checked = true;
            $attemptAnswer->checked_by_id = $request->user()->id;
            $attemptAnswer->save();
            $notCheckedAnswersExist = $attempt->answers()
                                            ->where('is_checked', false)
                                            ->exists();
            if(!$notCheckedAnswersExist){
                $finalizeAttemptChecking->execute($attempt);
            }
        });
        return $this->noContent();
    }
}
