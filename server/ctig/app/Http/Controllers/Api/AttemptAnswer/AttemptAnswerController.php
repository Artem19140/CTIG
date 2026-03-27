<?php

namespace App\Http\Controllers\Api\AttemptAnswer;

use App\Actions\Attempt\FinalizeAttemptCheckingAction;
use App\Actions\AttemptAnswer\HandleAttemptAnswerAction;
use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Exceptions\ForbiddenException;
use App\Http\Resources\AttemptAnswer\AttemptAnswerResource;
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
                            Request $request,
                            AttemptAnswer $attemptAnswer, 
                            HandleAttemptAnswerAction $handleAttemptAnswer
                        )
    {
        $foreignNational = $request->user();
        $attemptAnswer->load('attempt');
        $attempt = $attemptAnswer->attempt;
        if($foreignNational->id != $attempt->foreign_national_id){
            throw new ForbiddenException('Не найдено');// в Policy
        }

        if($attempt->isExpired() && $attempt->isActive()){
            $request->user()->tokens()->delete();
            $attempt->status = AttemptStatus::Finished;
            throw new BusinessException('Время экзамена вышло');
        }

        if(!$attempt->isActive()){
            $request->user()->tokens()->delete();
            throw new BusinessException('Попытка неактивна');
        }

        DB::transaction(function()use($attemptAnswer, $request,$attempt,$attemptAnswer){
            $attemptAnswer->execute($attemptAnswer,$request->input('attemptAnswer'));
            $attempt->last_activity_at = Carbon::now();
            $attempt->save();
        });
        
        return $this->noContent();
    }

    public function rate(
                            Request $request,
                            AttemptAnswer $attemptAnswer,
                            FinalizeAttemptCheckingAction $finalizeAttemptChecking
                        ){
        //лучше массивом объектов обновлять, чтобы за раз все выставить и сохранить на фронте
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
