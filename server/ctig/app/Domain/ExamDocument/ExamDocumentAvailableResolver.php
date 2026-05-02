<?php

namespace App\Domain\ExamDocument;

use App\Models\Exam;

class ExamDocumentAvailableResolver
{
    protected function availvable(){
        return [
            'available' => true,
            'reason' => null,
            'label' => null
        ];
    }

    protected function blocked(string $reason, string | null $label = null){
        return [
            'available' => false,
            'reason' => $reason,
            'label' => $label
        ];
    }

    public function resolve(Exam $exam){
        return [
            'list' => $this->list($exam),
            'codes' => $this->codes($exam),
            'protocol' => $this->protocol($exam),
            'results' => $this->results($exam)
        ];
    }

    protected function list(Exam $exam){
        
        if($this->hasNoEnrollment($exam)){
            return $this->blocked('no_enrollment');
        }
        return $this->availvable();
    }

    protected function codes(Exam $exam){
        if($exam->isCancelled()){
            return $this->blocked('cancelled');
        }

        if($this->hasNoEnrollment($exam)){
            return $this->blocked('no_enrollment');
        }

        if(!$exam->canGenerateCodes()){
            return $this->blocked('codes_generation_window_closed');
        }

        return $this->availvable();
    }

    protected function protocol(Exam $exam){
        if($exam->isCancelled()){
            return $this->blocked('cancelled');
        }

        if($exam->isPending()){
            return $this->blocked('pending');
        }

        if($this->hasNoEnrollment($exam)){
            return $this->blocked('no_enrollment');
        }

        if($this->hasNoAttempts($exam)){
            return $this->blocked('no_attempts');
        }

        if($this->hasActiveAttempts($exam)){
            return $this->blocked('has_active_attemtps');
        }
        
        return $this->availvable();
    }

    protected function results(Exam $exam){
        if($exam->isCancelled()){
            return $this->blocked('cancelled');
        }

        if($exam->isPending()){
            return $this->blocked('pending');
        }
        
        if($this->hasNoEnrollment($exam)){
            return $this->blocked('no_enrollment');
        }

        if($this->hasNoAttempts($exam)){
            return $this->blocked('no_attempts');
        }

        if($this->hasActiveAttempts($exam)){
            return $this->blocked('has_active_attemtps');
        }

        if($this->hasUncheckedAttemtps($exam)){
            return $this->blocked('not_checked','на проверке');
        }
        return $this->availvable();
    }


    protected function hasNoEnrollment(Exam $exam){
        return $exam->enrollments_count === 0;
    }

    protected function hasNoAttempts(Exam $exam){
        return $exam->has_attempts;
    }

    protected function hasUncheckedAttemtps(Exam $exam){
        return $exam->has_unchecked_attempts;
    }

    protected function hasActiveAttempts(Exam $exam){
        return $exam->has_active_attempts;
    }
}
