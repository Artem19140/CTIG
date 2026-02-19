<?php

namespace App\Http\Controllers\StudentAnswer;

use App\Enums\AttemptStatusEnum;
use App\Exceptions\BusinessException;
use App\Exceptions\ForbiddenException;
use App\Http\Resources\StudentAnswer\StudentAnswerResource;
use App\Models\StudentAnswer;
use App\Services\StudentAnswerCheckService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentAnswerController extends Controller
{
    public function index(Request $request)
    {
        $answers = StudentAnswer::where('attempt_id',$request->input('attemptId'))
                    ->get();
        return StudentAnswerResource::collection($answers);
    }

    public function update(Request $request, StudentAnswer $studentAnswer, StudentAnswerCheckService $studentAnswerCheckService)//StudentAnswerCheckService $studentAnswerCheckService
    {
        $student = $request->user();
        $request->validate([
            'studentAnswer' => ['nullable']
        ]);
        $studentAnswer->load('attempt');
        $attempt = $studentAnswer->attempt;
        if($student->id != $attempt->student_id){
            throw new ForbiddenException('Не найдено');// в Policy
        }

        if($attempt->isExpired()){
            $request->user()->tokens()->delete();
            $attempt->finish();
            throw new BusinessException('Время экзамена вышло');
        }

        if($attempt->status != AttemptStatusEnum::Started){
            $request->user()->tokens()->delete();
            throw new BusinessException('Попытка неактивна');
        }

        $studentAnswer->load(['taskVariant.task', 'taskVariant.answers']);
        $task = $studentAnswer->taskVariant->task;
        if(!$task->type->hasAnswers()){
            throw new BusinessException('Данному типу задания нельзя загрузить ответ');
        }
        if($task->type->autoCheck()){
            $answers = $request->input('studentAnswer');
            $rightAnswers = $studentAnswer->taskVariant->answers->where('is_correct')->pluck("content")->toArray();
            sort($rightAnswers);
            sort($answers);
            $isCorrect = $studentAnswerCheckService->check($task->type, $answers,$rightAnswers);
            if($isCorrect){
                $studentAnswer->mark = $task->mark;
            }else{
                $studentAnswer->mark = 0;
            }  
            //$studentAnswer->is_checked = true;
        }
        
        $studentAnswer->student_answer = $request->input('studentAnswer');
        $studentAnswer->save();
        return $this->noContent();
    }

    public function rate(Request $request, StudentAnswer $studentAnswer){
        $request->validate([
            'mark' => ['required', 'integer', 'min:0'] //нужно проверить, что у самого задания оценка не меньше марк
        ]);

        $studentAnswer->load(['taskVariant.task', 'attempt']);
        $attempt = $studentAnswer->attempt;

        if(!$attempt->status->canBeRated()){
            throw new BusinessException('Попытка либо уже проверена, либо аннулирована');
        }

        $task = $studentAnswer->taskVariant->task;
        if($task->mark < $request->input('mark')){
            throw new BusinessException('Выставленный балл больше, чем максимальный за задание');
        }
        if(!$task->type->autoCheck()){
            throw new BusinessException('Задание проверяется автоматически');
        }

        $studentAnswer->mark = $request->input('mark');
        $studentAnswer->is_checked = true;
        $studentAnswer->checked_by_id = $request->user()->id;
        //оценивай, если остались неоцененные вопросы - не ставь попытке checked, если нет - проверена + итоговый балл за попытку
        $studentAnswer->save();
        return $this->noContent();
    }
}
