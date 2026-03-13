<?php

namespace App\Http\Controllers\Web\StudentAnswer;

use App\Actions\Attempt\FinalizeAttemptCheckingAction;
use App\Actions\StudentAnswer\HandleStudentAnswerAction;
use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Exceptions\ForbiddenException;
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\StudentAnswer\StudentAnswerResource;
use App\Models\StudentAnswer;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Inertia\Inertia;

class StudentAnswerController extends Controller
{
    public function index(Request $request)
    {
        $answers = StudentAnswer::where('attempt_id',$request->input('attemptId'))
                    ->get();
        return StudentAnswerResource::collection($answers);
    }

    public function update(
                            Request $request,
                            StudentAnswer $studentAnswer, 
                            HandleStudentAnswerAction $handleStudentAnswer
                        )
    {
        $student = $request->user();
        $studentAnswer->load('attempt');
        $attempt = $studentAnswer->attempt;

        if($attempt->isExpired() && $attempt->isActive()){
            $attempt->finish();
            throw new BusinessException('Время экзамена вышло');
        }

        if(!$attempt->isActive()){
            throw new BusinessException('Попытка неактивна');
        }

        DB::transaction(function()use($studentAnswer, $request,$attempt,$handleStudentAnswer){
            $handleStudentAnswer->execute($studentAnswer,$request->input('studentAnswer'));
            $attempt->last_activity_at = Carbon::now();
            $attempt->save();
        });
        //$attempt->load(['answers.taskVariant'=>['answers','task']]);
        // return Inertia::render('Attempt/Attempt', [
        //     'attempt' => new AttemptResource($attempt)
        // ]);
    }

    public function rate(
                            Request $request,
                            StudentAnswer $studentAnswer,
                            FinalizeAttemptCheckingAction $finalizeAttemptChecking
                        ){
        //лучше массивом объектов обновлять, чтобы за раз все выставить и сохранить на фронте
        $request->validate([
            'mark' => ['required', 'integer', 'min:0'] //нужно проверить, что у самого задания оценка не меньше марк
        ]);

        if($studentAnswer->is_checked){
            throw new BusinessException('Задание уже проверено и оценено');
        }

        $studentAnswer->load(['taskVariant.task', 'attempt']);
        $attempt = $studentAnswer->attempt;

        if($attempt->status !== AttemptStatus::Finished){
            throw new BusinessException('Попытка незавершена или аннулирована');
        }

        $task = $studentAnswer->taskVariant->task;

        if($task->type->autoCheck()){
            throw new BusinessException('Задание проверяется автоматически');
        }

        if($task->mark < $request->input('mark')){
            throw new BusinessException('Выставленный балл больше, чем максимально возможный за задание');
        }

        DB::transaction(function () use($studentAnswer, $attempt,$request, $finalizeAttemptChecking) {
            $studentAnswer->mark = $request->input('mark');
            $studentAnswer->is_checked = true;
            $studentAnswer->checked_by_id = $request->user()->id;
            $studentAnswer->save();
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
