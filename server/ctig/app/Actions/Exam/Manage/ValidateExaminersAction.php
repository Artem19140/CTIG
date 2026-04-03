<?php

namespace App\Actions\Exam\Manage;

use App\Enums\UserRoles;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
class ValidateExaminersAction{
    public function execute(array $examiners, Carbon $examBeginTime, Carbon $examEndTime, int | null $examId = null ){
        $conflictExaminers = Exam::where('is_cancelled', false)
                                ->where('begin_time', '<=', $examEndTime)
                                ->where('end_time', '>=', $examBeginTime)
                                ->whereHas('examiners', function (Builder $query) use ($examiners): void {
                                    $query->whereIn('users.id', $examiners);
                                })
                                
                                ->when($examId, function (Builder $query) use($examId){
                                    $query->where('id', '<>', $examId);
                                })
                                ->exists();

        $busyExaminers = User::whereHas('exams', function(Builder $query) use($examiners,$examBeginTime,$examEndTime, $examId){
                $query->where('begin_time', '<=', $examEndTime)
                    ->where('end_time', '>=', $examBeginTime)
                    ->where('is_cancelled', false)
                    ->whereHas('examiners', function (Builder $query) use ($examiners): void {
                                    $query->whereIn('users.id', $examiners);
                                })
                    ->when($examId, function (Builder $query) use($examId){
                                    $query->where('id', '<>', $examId);
                                });
        })->get();
        if($busyExaminers->isNotEmpty()){
            $names = $busyExaminers->implode('full_name', ', ');
            throw new BusinessException("Экзаменаторы $names заняты в это время");
        }
        if($conflictExaminers){
            //$conflictExaminers->implode('full_name', ', ');
            throw new BusinessException("Некоторые экзаменаторы заняты в это время");
        }

        $examiners = User::with('roles')->whereIn('id', $examiners)->get();

        $notActive = $examiners->filter(function($examiner){
            return !$examiner->is_active;
        });

        if($notActive->isNotEmpty()){
            $names = $notActive->implode('full_name', ', ');
            throw new BusinessException("$names уже не работает(-ют) в организации");
        }

        $noExaminerRole = $examiners->filter(function($examiner){
            return !$examiner->hasRole(UserRoles::Examiner->value);
        });

        if($noExaminerRole->isNotEmpty()){
            $names = $noExaminerRole->implode('full_name', ', ');
            throw new BusinessException("$names не имеет(-ют) роли экзаменатора");
        }

        foreach($examiners as $examiner){
            if(!$examiner->is_active){
                throw new BusinessException("$examiner->full_name уже не работает в организации");
            }
        }
    }
}