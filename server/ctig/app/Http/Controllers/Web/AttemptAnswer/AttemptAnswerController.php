<?php

namespace App\Http\Controllers\Web\AttemptAnswer;

use App\Actions\Attempt\CheckAttemptIsActiveAction;
use App\Actions\Attempt\FinalizeAttemptCheckingAction;
use App\Actions\AttemptAnswer\HandleAttemptAnswerAction;
use App\Enums\AttemptStatus;
use App\Exceptions\BusinessException;
use App\Http\Requests\AttemptAnswer\AttemptAnswerRequest;
use App\Http\Resources\Attempt\AttemptResource;
use App\Http\Resources\AttemptAnswer\AttemptAnswerResource;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\TaskVariant;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Inertia\Inertia;

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
                            CheckAttemptIsActiveAction $checkAttempt,
                            HandleAttemptAnswerAction $handleAttemptAnswer
                        )
    {
        $student = $request->user();
        if($attempt->student_id !== $student->id){
            abort(403);
        }
        $isActive = $checkAttempt->execute($attempt);

        if(!$isActive){
            $attempt->finish();
            return redirect()->route('login');
        }
        $attemptAnswer = 1;

        DB::transaction(function()use($attemptAnswer, $request,$attempt,$handleAttemptAnswer){
            $handleAttemptAnswer->execute($attemptAnswer,$request->input('attemptAnswer'));
            $attempt->last_activity_at = Carbon::now();
            $attempt->save();
        });
        return response()->noContent();
        //$attempt->load(['answers.taskVariant'=>['answers','task']]);
        return Inertia::render('Attempt/Attempt', [
            'attempt' => new AttemptResource($attempt),
        ]);
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
