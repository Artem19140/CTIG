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
        $conflictExaminers = Exam::where('begin_time', '<=', $examEndTime)
                                ->where('end_time', '>=', $examBeginTime)
                                ->whereHas('examiners', function (Builder $query) use ($examiners): void {
                                    $query->whereIn('users.id', $examiners);
                                })
                                ->when($examId, function (Builder $query) use($examId){
                                    $query->where('id', '<>', $examId);
                                })
                                ->exists();
        
        if($conflictExaminers){
            //$conflictExaminers->implode('full_name', ', ');
            throw new BusinessException("Некоторые экзаменаторы заняты в это время");
        }

        $examiners = User::with('roles')->whereIn('id', $examiners)->get();

        foreach($examiners as $examiner){
            if(!$examiner->hasRole(UserRoles::Examiner->value)){
                throw new BusinessException("$examiner->full_name не имеет роли экзаменатора");
            }
        }

        foreach($examiners as $examiner){
            if(!$examiner->is_active){
                throw new BusinessException("$examiner->full_name уже не работает в организации");
            }
        }
    }
}