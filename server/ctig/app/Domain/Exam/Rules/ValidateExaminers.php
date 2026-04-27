<?php

namespace App\Domain\Exam\Rules;

use App\Enums\UserRoles;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ValidateExaminers{
    public function execute(array $examinersIds, Carbon $beginTime, Carbon $endTime, int | null $examId = null ){

        $parralellExaminersExams = $this->getParralellExaminersExams($beginTime, $endTime, $examinersIds, $examId);

        $examiners = User::with('roles')->whereIn('id', $examinersIds)->get();

        $this->ensureNoBusyExaminers($parralellExaminersExams, $examiners);

        $this->ensureAllExaminersActive($examiners);

        $this->ensureAllHasRoleExaminer($examiners);
    }

    protected function getParralellExaminersExams(
        Carbon $beginTime, 
        Carbon $endTime, 
        array $examiners, 
        int | null $examId = null
    ):Collection{
        return Exam::whereBeginTimeLess($endTime)
            ->whereEndTimeMore($beginTime)
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
    }

    protected function ensureNoBusyExaminers(Collection $parallelExaminersExams, Collection $examiners): void{
        $busyExaminers = $parallelExaminersExams
            ->pluck('examiners')
            ->flatten()
            ->unique('id');

        $busyExaminersIds = $busyExaminers->pluck('id');

        $examinerIds = $examiners->pluck('id');

        $intersection = $busyExaminersIds->intersect($examinerIds);

        if ($intersection->isEmpty()) {
            return;
        }

        $names = $busyExaminers
            ->whereIn('id', $intersection)
            ->implode('full_name_short', ', ');

        throw new BusinessException(
            "Выбранные экзаменаторы недоступны в указанное время: $names"
        );
    }

    protected function ensureAllExaminersActive(Collection $examiners):void{
        $notActive = $examiners->filter(function($examiner){
            return !$examiner->is_active;
        });

        if($notActive->isNotEmpty()){
            $names = $notActive->implode('full_name', ', ');
            throw new BusinessException("$names уже не работает(-ют) в организации");
        }
    }

    protected function ensureAllHasRoleExaminer(Collection $examiners):void{
        $noRoleExaminer = $examiners->filter(function($examiner){
            return !$examiner->hasRole(UserRoles::Examiner->value);
        });

        if($noRoleExaminer->isNotEmpty()){
            $names = $noRoleExaminer->implode('full_name', ', ');
            throw new BusinessException("$names не имеет(-ют) роли экзаменатора");
        }
    }
}