<?php

namespace App\Actions\Student;

use App\Exceptions\BusinessException;
use App\Exceptions\EntityNotFoundExсeption;
use App\Models\Exam;
use App\Models\Student;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class CreateStudentStatementAction{
    public function execute(int $examId, Student $student, User $user){ //Student $student, Exam $exam
        $exam = Exam::with(['examType'])
                    ->find($examId);
        if(!$exam){
            throw new EntityNotFoundExсeption('Экзамен');
        }
        $exam = $student->exams()
            ->where('exams.id', $examId)
            ->first();
        if(!$exam){
            throw new BusinessException('Такой записи на экзамен не сущетствует');
        }
        $user->load('organization');

        return Pdf::loadView('templates.statement-student',[
            'student' => $student,
            'exam' => $exam,
            'user' => $user,
            'reg_number' => $exam->pivot->reg_number,
            'organization' => $user->organization
        ]);

        // Отдаем клиенту на скачивание
        
    }
}