<?php

namespace App\Domain\Exam\Validator;

use App\Domain\Center\CenterContext;
use App\Enums\EmployeeRole;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;

class ExaminersValidator{
    public function execute(
        array $examinersIds, 
        Carbon $beginTime, 
        Carbon $endTime, 
        int | null $examId = null 
    ):void{

        $parralellExaminersExams = 
            $this->getParralellExaminersExams($beginTime, 
            $endTime, 
            $examinersIds, 
            $examId
        );

        $examiners = Employee::with('roles')
            ->whereIn('id', $examinersIds)
            ->get();

        $this->ensureHasNoExaminersConflict(
            $parralellExaminersExams, 
            $examiners
        );

        $this->ensureAllExaminersActive($examiners);

        $this->ensureAllHasRoleExaminer($examiners);
    }

    protected function getParralellExaminersExams(
        Carbon $beginTime, 
        Carbon $endTime, 
        array $examiners, 
        int | null $examId = null
    ):Collection{

        return Exam::query()
            ->forCenter(app(CenterContext::class)->id())
            ->where('begin_time', '<=',$endTime)
            ->where('end_time', '>=',$beginTime)
            ->notCancelled()

            ->with('examiners', function (BelongsToMany $query)use ($examiners): void{
                $query->whereIn('employees.id', $examiners);
            })

            ->whereHas('examiners', function (Builder $query) use ($examiners): void {
                $query->whereIn('employees.id', $examiners);
            })

            ->when($examId, function (Builder $query) use($examId){
                $query->where('id', '<>', $examId);
            })

            ->get();
    }

    protected function ensureHasNoExaminersConflict(
        Collection $parallelExaminersExams, 
        Collection $examiners
    ): void{
        $busyExaminers = $parallelExaminersExams
            ->pluck('examiners')
            ->flatten()
            ->unique('id');

        $busyExaminersIds = $busyExaminers->pluck('id');

        $examinerIds = $examiners->pluck('id');

        $intersection = $busyExaminersIds
            ->intersect($examinerIds);

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
            Log::warning('trying to select examiners, but they are not active',[
                'ids' => $notActive->pluck('id')->toArray()
            ]);
            $names = $notActive->implode('full_name', ', ');
            throw new BusinessException("$names уже не работает(-ют) в организации");
        }
    }

    protected function ensureAllHasRoleExaminer(Collection $examiners):void{
        $noRoleExaminer = $examiners->filter(function($examiner){
            return !$examiner->hasRole(EmployeeRole::Examiner->value);
        });

        if($noRoleExaminer->isNotEmpty()){
            Log::warning('trying to select examiners, but they have no role examiner',[
                'ids' => $noRoleExaminer->pluck('id')->toArray()
            ]);
            $names = $noRoleExaminer->implode('full_name', ', ');
            throw new BusinessException("$names не имеет(-ют) роли экзаменатора");
        }
    }
}