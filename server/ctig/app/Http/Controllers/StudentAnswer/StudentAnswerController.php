<?php

namespace App\Http\Controllers\StudentAnswer;

use App\Enums\AttemptStatusEnum;
use App\Exceptions\BusinessException;
use App\Http\Resources\StudentAnswer\StudentAnswerResource;
use App\Models\StudentAnswer;
use App\Services\StudentAnswerCheckService;
use DB;
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
            'studentAnswer' => ['nullable', 'array']
        ]);
        $studentAnswer->load('attempt');
        $attempt = $studentAnswer->attempt;
        // if($student->id != $attempt->student_id){
        //     throw new ForbiddenException('Не найдено');// в Policy
        // }

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
        DB::transaction(function()use($studentAnswer, $task, $request, $studentAnswerCheckService){
            if($task->type->autoCheck()){
            $answers =(array) $request->input('studentAnswer',[]);
            $rightAnswers = $studentAnswer->taskVariant->answers->where('is_correct')->pluck("content")->toArray();
            sort($rightAnswers);
            sort($answers);
            $isCorrect = $studentAnswerCheckService->check($task->type, $answers,$rightAnswers);
            if($isCorrect){
                $studentAnswer->mark = $task->mark;
            }else{
                $studentAnswer->mark = 0;
            }  
                $studentAnswer->is_checked = true;
            }
            $studentAnswer->last_activity_at = now();
            $studentAnswer->student_answer = $request->input('studentAnswer');
            $studentAnswer->save();
        });
        
        return $this->noContent();
    }

    public function rate(Request $request, StudentAnswer $studentAnswer){
        //лучше массивом объектов обновлять, чтобы за раз все выставить и сохранить на фронте
        $request->validate([
            'mark' => ['required', 'integer', 'min:0'] //нужно проверить, что у самого задания оценка не меньше марк
        ]);

        if($studentAnswer->is_checked){
            throw new BusinessException('Задание уже проверено и оценено');
        }

        $studentAnswer->load(['taskVariant.task', 'attempt']);
        $attempt = $studentAnswer->attempt;

        if(!$attempt->status->canBeRated()){
            throw new BusinessException('Попытка аннулирована');
        }

        $task = $studentAnswer->taskVariant->task;

        if($task->type->autoCheck()){
            throw new BusinessException('Задание проверяется автоматически');
        }

        if($task->mark < $request->input('mark')){
            throw new BusinessException('Выставленный балл больше, чем максимально возможный за задание');
        }

        DB::transaction(function () use($studentAnswer, $attempt,$request) {
            $studentAnswer->mark = $request->input('mark');
            $studentAnswer->is_checked = true;
            $studentAnswer->checked_by_id = $request->user()->id;
            $studentAnswer->save();
            $notCheckedAnswersExist = StudentAnswer::where('attempt_id', $attempt->id)
                            ->where('is_checked', false)
                            ->exists();
            if(!$notCheckedAnswersExist){
                $attempt->checked();
                $markCount = StudentAnswer::where('attempt_id',$attempt->id)
                                ->sum('mark');
                $attempt->total_mark = $markCount;
                $attempt->save();
                //мб 200 с сообщением вернуть
            }
        });
        return $this->noContent();
    }
}
