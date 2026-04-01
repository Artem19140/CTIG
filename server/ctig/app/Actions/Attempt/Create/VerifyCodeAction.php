<?php

namespace App\Actions\Attempt\Create;

use App\Exceptions\BusinessException;
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

        $foreignNational->exam_code = null;
        $foreignNational->exam_code_expired_at = null;
        $foreignNational->save();

       return $foreignNational;
    }
}