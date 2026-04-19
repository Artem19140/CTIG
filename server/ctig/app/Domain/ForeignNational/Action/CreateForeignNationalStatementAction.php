<?php

namespace App\Domain\ForeignNational\Action;

use App\Exceptions\BusinessException;
use App\Exceptions\EntityNotFoundExсeption;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class CreateForeignNationalStatementAction{
    public function execute(int $examId, ForeignNational $foreignNational, User $user){ 
        $exam = Exam::with(['type'])
                    ->find($examId);
        if(!$exam){
            throw new EntityNotFoundExсeption('Экзамен');
        }
        $exam = $foreignNational->exams()
            ->where('exams.id', $examId)
            ->first();
        if(!$exam){
            throw new BusinessException('Такой записи на экзамен не сущетствует');
        }
        //$user->load('center');

        return Pdf::loadView('templates.statement-foreign-national',[
            'foreignNational' => $foreignNational,
            'exam' => $exam,
            'user' => $user,
            'reg_number' => $exam->pivot->reg_number,
            'center' => $user->center
        ]);  
    }
}