<?php

namespace App\Domain\Exam\Rules;

use App\Enums\UserRoles;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Log;
class ValidateExaminers{
    public function execute(array $examiners, Carbon $examBeginTime, Carbon $examEndTime, int | null $examId = null ){
        $conflictExams = Exam::whereBeginTimeLess($examEndTime)
                                ->whereEndTimeMore($examBeginTime)
                                ->notCancelled()
                                ->with('examiners', function (BelongsToMany $query)use ($examiners): void{
                                    $query->whereIn('users.id', $examiners);
                                })
                                ->whereHas('examiners', function (Builder $query) use ($examiners): void {
                                    $query->whereIn('users.id', $examiners);
                                })
                                ->when($examId, function (Builder $query) use($examId){
                                    $query->where('id', '<>', $examId);
                                })
                                ->get();
        $busyExaminers = $conflictExams->pluck('examiners')->flatten();

        if($conflictExams->isNotEmpty()){
            $names = $busyExaminers->implode('full_name_short', ', ');
            throw new BusinessException("Выбранные экзаменаторы недоступны в указанное время: $names");
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
                throw new BusinessException("$examiner->full_name уже не работает(-ют) в организации");
            }
        }
    }
}