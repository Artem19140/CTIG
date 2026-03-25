<?php

namespace App\Actions\Exam;

use App\Models\Exam;
use App\Actions\Counter\GetGroupNumberAction;

class SetSessionAndGroupNumberAction{
    public function __construct(
        protected GetGroupNumberAction $getGroupNumber
    ){}
    public function execute(Exam $exam):Exam{
        
        $sessionNumber = Exam::whereBetween('begin_time', [
            $exam->begin_time->startOfYear(),
            $exam->begin_time->startOfDay()
        ])
        ->get()
        ->groupBy(function($exam) {
            return $exam->begin_time->toDateString();
        })
        ->count();
        $exam->group = $this->getGroupNumber->execute();
        $exam->session = $sessionNumber + 1;
        $exam->save();
        return $exam;
    }
}