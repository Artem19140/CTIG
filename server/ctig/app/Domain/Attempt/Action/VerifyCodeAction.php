<?php

namespace App\Domain\Attempt\Action;

use App\Exceptions\BusinessException;
use App\Models\Enrollment;
use App\Models\ForeignNational;
use Carbon\Carbon;

class VerifyCodeAction{

    public function execute(string $code):ForeignNational{
        $foreignNational = ForeignNational::where('exam_code', $code)
                        ->first();
        if(!$foreignNational){
            throw new BusinessException('Код не найден');
        }

        if($foreignNational->exam_code_expired_at < Carbon::now()){
            throw new BusinessException('Истек срок действия кода');
        }
        $enrollment = Enrollment::for($foreignNational->id, $foreignNational->exam_id)->first();

        if(!$enrollment->hasPayment()){
            throw new BusinessException('Экзамен не оплачен');
        }
        $foreignNational->exam_code = null;
        $foreignNational->exam_code_expired_at = null;
        $foreignNational->save();

       return $foreignNational;
    }
}