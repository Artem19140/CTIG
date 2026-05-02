<?php

namespace App\Domain\Attempt\Action;

use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;


class VerifyCodeAction{

    public function execute(string $code):Enrollment{
        $enrollment = $this->findOrFailEnrollmentByCode($code);
        $this->ensureCodeNotExpired($enrollment->exam_code_expired_at);
        $this->ensureHasPayment($enrollment->hasPayment());
        $this->makeCodeUsed($enrollment);
        return $enrollment;
    }

    protected function findOrFailEnrollmentByCode(string $code){
        $enrollment = Enrollment::where('exam_code', $code)
            ->first();
                
        if(!$enrollment){
            throw ValidationException::withMessages([
                'code' => 'Код не найден'
            ]);
        }
        return $enrollment;
    }

    protected function ensureCodeNotExpired(Carbon $expiredAt){
        if($expiredAt < Carbon::now()){
            throw ValidationException::withMessages([
                'code' => 'Истек срок действия кода'
            ]);
        }
    }

    protected function ensureHasPayment(bool $hasPayment){
        if(!$hasPayment){
            throw ValidationException::withMessages([
                'code' => 'Экзамен не оплачен'
            ]);
        }
    }

    protected function makeCodeUsed(Enrollment $enrollment){
        $enrollment->exam_code = null;
        $enrollment->exam_code_used_at = Carbon::now();
        $enrollment->save();
    }
}