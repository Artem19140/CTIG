<?php

namespace App\Actions\Exam;

use App\Enums\TokenAbilities;
use App\Exceptions\BusinessException;
use App\Models\Exam;
use App\Models\ForeignNational;
use Carbon\Carbon;
use DB;

class VerifyExamCodeAction{

    public function execute(string $code):ForeignNational{
        $foreignNational = ForeignNational::where('exam_code', $code)
                        ->first();
        if(!$foreignNational){
            throw new BusinessException('Код не найден');
        }

        if($foreignNational->exam_code_expired_at < Carbon::now()){
            throw new BusinessException('Истек срок действия кода');
        }

        

        // DB::transaction(function () use($student){
        //     $student->exam_code = null;
        //     $student->exam_code_expired_at = null;
        //     $student->save();
        // });
       return $foreignNational;
    }
}