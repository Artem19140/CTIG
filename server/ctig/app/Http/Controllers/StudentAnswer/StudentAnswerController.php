<?php

namespace App\Http\Controllers\StudentAnswer;

use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentAnswerController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //экзмен, студент, блок, ответ, вопрос существуют, есть у студента активная попытка, экзамен не закончен, а попытка относится к экзамену, и время не кончилось, а также что студеднт не снят с экзамен
    }

    public function show(StudentAnswer $studentAnswer)
    {
        //
    }

    public function update(Request $request, StudentAnswer $studentAnswer)
    {
        //
    }

    public function destroy(StudentAnswer $studentAnswer)
    {
        //
    }
}
