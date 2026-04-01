<?php

namespace App\Actions\Exam\Manage;

use App\Enums\UserRoles;
use App\Http\Dto\ExamDto;
use App\Models\Address;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\User;
use Carbon\Carbon;
use App\Exceptions\BusinessException;
use DB;
use Illuminate\Database\Eloquent\Builder;
use App\Actions\Exam\Manage\ValidateExamCreationAction;
// dump([
        //     'exam_utc' => $examBeginTime->toDateTimeString(),
        //     'now_utc' => Carbon::now()->utc()->toDateTimeString(),
        //     'now_local' => Carbon::now($user->organization->time_zone)->toDateTimeString(),
        // ]);
final class CreateExamAction{
    public function __construct(
        protected ValidateExamCreationAction $validateExamCreation
    ){}
    public function execute(ExamDto $examDto, User $user){
        $duration = $this->validateExamCreation->execute($examDto, $user);
        $exam = DB::transaction(function () use ($examDto, $user, $duration) {
            $exam = Exam::create([
                    'begin_time' => $examDto->beginTime,
                    'begin_time_utc' => $examDto->beginTime->copy()->utc(),
                    'address_id' => $examDto->addressId,
                    'capacity' => $examDto->capacity,
                    'exam_type_id' => $examDto->examTypeId,
                    'comment' => $examDto->comment,
                    'creator_id'=> $user->id,
                    'end_time' => $examDto->beginTime->copy()->addMinutes($duration),
                    'organization_id' => $user->organization->id
            ]);
        
            $exam->examiners()->attach($examDto->examiners, ['organization_id'  => $user->organization->id]);
            return $exam;
        });        
        return $exam;
    }
}