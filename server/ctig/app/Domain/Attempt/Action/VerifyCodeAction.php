<?php

namespace App\Domain\Attempt\Action;

use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;


class VerifyCodeAction{

    public function execute(string $code):Enrollment{
        $enrollment = Enrollment::where('exam_code', $code)
                        ->first();
        if(!$enrollment){
            throw ValidationException::withMessages([
                'code' => 'Код не найден'
            ]);
        }

        if($enrollment->exam_code_expired_at < Carbon::now()){
            throw ValidationException::withMessages([
                'code' => 'Истек срок действия кода'
            ]);
        }
        if(!$enrollment->hasPayment()){
            throw ValidationException::withMessages([
                'code' => 'Экзамен не оплачен'
            ]);
        }

        // $enrollment->exam_code = null;
        // $enrollment->exam_code_expired_at = null;
        $enrollment->exam_code_used_at = Carbon::now($enrollment->time_zone);
        //$enrollment->save();

       return $enrollment;
    }
}