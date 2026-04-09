<?php

namespace App\Domain\Attempt\Action;

use App\Exceptions\BusinessException;
use App\Models\Enrollment;
use Carbon\Carbon;

class VerifyCodeAction{

    public function execute(string $code):Enrollment{
        $enrollment = Enrollment::where('exam_code', $code)
                        ->first();
        if(!$enrollment){
            throw new BusinessException('Код не найден');
        }

        if($enrollment->exam_code_expired_at < Carbon::now()){
            throw new BusinessException('Истек срок действия кода');
        }
        if(!$enrollment->hasPayment()){
            throw new BusinessException('Экзамен не оплачен');
        }

        // $enrollment->exam_code = null;
        // $enrollment->exam_code_expired_at = null;
        // $enrollment->exam_code_used_at = Carbon::now($enrollment->center->time_zone);
        //$enrollment->save();

       return $enrollment;
    }
}