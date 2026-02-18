<?php

namespace App\Actions\Exam;

use App\Enums\ExamStatusEnum;
use App\Http\Resources\Student\StudentResource;
use App\Models\Exam;
use Carbon\Carbon;
use App\Exceptions\BusinessException;
use DB;
use Illuminate\Database\QueryException;
use Response;
use Barryvdh\DomPDF\Facade\Pdf;

final class CreateStudentsCodesForExamAction{
    public function execute(Exam $exam){
        if($exam->isPassed()){
            throw new BusinessException('Экзмен уже прошел');
        }

        if($exam->status != ExamStatusEnum::Expected && $exam->status != ExamStatusEnum::Started){
            throw new BusinessException('Коды на данный экзамен сформировать уже нельзя');
        }

        $examBeginTime = Carbon::parse($exam->begin_time);
        $minutesBieforeBegin = $examBeginTime->copy()->diffInMinutes(Carbon::now(), false);

        $minutes = config('exam.code_generation_before_minutes'); 
        if(-$minutesBieforeBegin >= $minutes){
            throw new BusinessException("Коды можно сформировать минимум за 40 минут до экзамена");
        }

        $studentsExists = $exam->students()->exists();
        if(!$studentsExists){
            throw new BusinessException('На экзамен не записано ни одного студента');
        }

        $students = $exam->students;
        
        foreach($students as $student){
            if($student->exam_code || $student->exam_id === $exam->id){
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
        $html = '
<style>
    body {
        font-size: 16px;
        line-height: 1.3;
    }
    h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    th, td {
        border: 1px solid #000;
        padding: 5px;
        text-align: left;
        vertical-align: top;
    }
    th {
        background-color: #f0f0f0;
        font-weight: bold;
    }
    tr:nth-child(even) td {
        background-color: #f9f9f9;
    }
</style>
<h2>Коды студентов</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Фамилия</th>
        <th>Имя</th>
        <th>Серия</th>
        <th>Номер</th>
        <th>Код</th>
    </tr>';

foreach ($students as $s) {
    $html .= '<tr>';
    $html .= "<td>{$s->id}</td>";
    $html .= "<td>{$s->surname}</td>";
    $html .= "<td>{$s->name}</td>";
    $html .= "<td>{$s->passport_series}</td>";
    $html .= "<td>{$s->passport_number}</td>";
    $html .= "<td>{$s->exam_code}</td>";
    $html .= '</tr>';
}

$html .= '</table>';
        
        $pdf = Pdf::loadHTML($html);

        return response($pdf->stream('codes.pdf'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="codes.pdf"');
        //=====
        return StudentResource::collection($students);
    }

    protected function createCode(){

    }
}