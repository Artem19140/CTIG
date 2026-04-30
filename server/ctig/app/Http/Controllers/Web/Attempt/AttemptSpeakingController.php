<?php

namespace App\Http\Controllers\Web\Attempt;

use App\Domain\Attempt\Query\GetAttemptSpeakingTasksQuery;
use App\Exceptions\BusinessException;
use App\Http\Resources\Attempt\AttemptResource;
use App\Models\Attempt;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class AttemptSpeakingController
{
    public function show(
        Attempt $attempt, 
        GetAttemptSpeakingTasksQuery $getAttemptSpeakingQuery
    ){
        $exam = Exam::findOrFail($attempt->exam_id);

        Gate::authorize('exam-manage-access', $exam);

        $this->ensureAttemptHasSpeaking($attempt); 
        $this->ensureExamStarted($attempt);
        $this->ensureTodayIsExamDay($attempt);

        if(!$attempt->speaking_started_at){
            throw new BusinessException('Говорение еще не начато');
        }

        $this->ensureSpeakingNotFinished($attempt);

        $attempt = $getAttemptSpeakingQuery->execute($attempt);

        return new AttemptResource($attempt);
    }

    public function start(Attempt $attempt){
        $this->ensureAttemptHasSpeaking($attempt);
        $this->ensureExamStarted($attempt);
        $this->ensureTodayIsExamDay($attempt);

        if($attempt->speaking_started_at){
            throw new BusinessException('Говорение уже началось');
        }

        $attempt->speaking_started_at = Carbon::now();
        $attempt->save();
        return new AttemptResource($attempt);
    }

    public function finish(Attempt $attempt){

        $this->ensureAttemptHasSpeaking($attempt);
        $this->ensureSpeakingNotFinished($attempt);
        $this->ensureExamStarted($attempt);
        $this->ensureTodayIsExamDay($attempt);

        $attempt->speaking_finished_at = Carbon::now();
        $attempt->save();
        return response()->noContent();
    }

    protected function ensureSpeakingNotFinished(Attempt $attempt){
        if($attempt->speaking_finished_at){
            throw new BusinessException('Говорение уже завершено');
        }
    }

    protected function ensureAttemptHasSpeaking(Attempt $attempt){
        if(!$attempt->exam->hasSpeaking()){
            throw new BusinessException('У данной попытки нет заданий на говорение');
        }
    }

    protected function ensureExamStarted(Attempt $attempt){
        if(!$attempt->exam->begin_time->isPast()){
            throw new BusinessException('Говорение доступно после начала экзамена');
        }
    }

    protected function ensureTodayIsExamDay(Attempt $attempt){
        if(!$attempt->exam->begin_time->isToday()){
            throw new BusinessException('Говорение доступно в день экзамена');
        }
    }
}
