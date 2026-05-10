<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\Exam;
use App\Models\User;


class ExamPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Exam $exam): bool{
        if($user->hasAnyRole(
            UserRoles::Operator->value, 
            UserRoles::Director->value, 
            UserRoles::Scheduler->value,
            UserRoles::SuperAdmin->value
        )){
            return true;
        }
        return $this->isExaminer($user, $exam);
    }

    public function monitoring(User $user, Exam $exam): bool{
        return $this->isExaminer($user, $exam);
    }

    public function checking(User $user, Exam $exam): bool{
        return $this->isExaminer($user, $exam);
    }


    protected function isExaminer(User $user, Exam $exam){
        if(!$user->hasRole(UserRoles::Examiner->value)){
            return false;
        }
        return $user->exams()
            ->wherePivot('exam_id', $exam->id)
            ->exists();
    }

}
