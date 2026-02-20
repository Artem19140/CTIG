<?php

namespace App\Actions\Exam;

use App\Models\Attempt;

class CheckPassingThresholdAction{
    public function execute(Attempt $attempt): bool{
        $studentAnswers = $attempt->answers()->with('taskVariant.task.subblock.block')->get();

        $answersByBlock = $studentAnswers->groupBy(function($answer){
            return $answer->taskVariant->task->subblock->block->id;
        });

        foreach($answersByBlock as $block=>$answers){
            $blockMinMark = $answers->first()->taskVariant->task->subblock->block->min_mark;
            $answersMarkSum = $answers->sum('mark');
            if ($blockMinMark > $answersMarkSum){
                return false;
            }
        }

        $answersBySubBlock = $studentAnswers->groupBy(function($answer){
            return $answer->taskVariant->task->subblock->id;
        });

        foreach($answersBySubBlock as $subblock=>$answers){
            $subblockMinMark = $answers->first()->taskVariant->task->subblock->min_mark;
            $answersMarkSum = $answers->sum('mark');
            if ($subblockMinMark > $answersMarkSum){
                return false;
            }
        }
        return true;
    }

}