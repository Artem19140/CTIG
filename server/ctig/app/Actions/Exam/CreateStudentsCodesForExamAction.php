<?php

namespace App\Actions\Exam;

use App\Enums\ExamStatus;
use App\Http\Resources\Student\StudentResource;
use App\Models\Exam;
use Carbon\Carbon;
use App\Exceptions\BusinessException;
use DB;
use Illuminate\Database\QueryException;
use Response;

final class CreateStudentsCodesForExamAction{
    public function execute(Exam $exam){
        // if($exam->end_time < Carbon::now()){
        //     throw new BusinessException('Экзамен уже прошел');
        // }

        // if($exam->status != ExamStatus::Pending && $exam->status != ExamStatus::Started){
        //     throw new BusinessException('Коды на данный экзамен сформировать уже нельзя');
        // }

        $examBeginTime = Carbon::parse($exam->begin_time);
        $minutesBieforeBegin = $examBeginTime->copy()->diffInMinutes(Carbon::now(), false);

        $minutes = config('exams.code_generation_before_minutes');
        // if($minutesBieforeBegin >= $minutes){
        //     throw new BusinessException("Коды можно сформировать минимум за 40 минут до экзамена");
        // }

        $studentsExists = $exam->students()->exists();
        if(!$studentsExists){
            throw new BusinessException('На экзамен не записано ни одного студента');
        }

        $students = $exam->students;
        
        foreach($students as $student){
            if($student->exam_code){//или есть попытка экзамена активная!
                continue;
            }
            
            do{
                $rnd = random_int(1, 9999);
                $code = str_pad($rnd, 4, '0', STR_PAD_LEFT);
                $saved = false;
                try{
                    $student->exam_code = $code;
                    $student->exam_id = $exam->id;
                    $student->exam_code_expired_at = Carbon::now()->addHour();
                    $student->save();
                    $saved = true;
                }catch(QueryException $e){
                    if ($e->getCode() !== '23000') {
                        throw $e;
                    }
                    $saved = false;
                }
            }while(!$saved);

        }

        //------
           $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="students_codes.csv"',
    ];

    $callback = function() use ($students) {
        $fp = fopen('php://output', 'w');

        // BOM для Excel (UTF-8)
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

        // Заголовки колонок
        fputcsv($fp, [
            'ID',
            'Surname',
            'Name',
            'Patronymic',
            'Date of Birth',
            'Surname Latin',
            'Name Latin',
            'Patronymic Latin',
            'Passport Number',
            'Passport Series',
            'Exam Code'
        ]);

        // Данные студентов
        foreach ($students as $student) {
            fputcsv($fp, [
                $student->id,
                $student->surname,
                $student->name,
                $student->patronymic,
                $student->date_birth,
                $student->surname_latin,
                $student->name_latin,
                $student->patronymic_latin,
                $student->passport_number,
                $student->passport_series,
                $student->exam_code
            ]);
        }

        fclose($fp);
    };

    return Response::stream($callback, 200, $headers);
        //=====
        return StudentResource::collection($students);
    }

    protected function createCode(){

    }
}