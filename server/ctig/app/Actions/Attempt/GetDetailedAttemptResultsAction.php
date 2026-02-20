<?php 

namespace App\Actions\Attempt;

use App\Models\Attempt;

class GetDetailedAttemptResultsAction{
    public static function execute(Attempt $attempt){
        $studentAnswers = $attempt->answers()->with('taskVariant.task.subblock.block')->get();
        $result = [];
        $answersByBlock = $studentAnswers->groupBy(function($answer){
            return $answer->taskVariant->task->subblock->block->id;
        });

        foreach($answersByBlock as $blockId => $blockAnswers){
            $block = $blockAnswers->first()->taskVariant->task->subblock->block;
            $answersBlockMarkSum = $blockAnswers->sum('mark');

            $answersBySubBlock = $blockAnswers->groupBy(function($answer){
                return $answer->taskVariant->task->subblock->id;
            });

            $subblockResults = [];
            $allSubblocksPassed = true; 
            foreach($answersBySubBlock as $subblockId => $subblockAnswers){
                $subblock = $subblockAnswers->first()->taskVariant->task->subblock;
                $answersSubblockMarkSum = $subblockAnswers->sum('mark');
                $isPassedSubblock = $subblock->min_mark <= $answersSubblockMarkSum;
                if(!$isPassedSubblock){
                    $allSubblocksPassed = false; 
                }
                $subblockResults[] = [
                    'id' => $subblockId,
                    'min_mark' => $subblock->min_mark,
                    'name' => $subblock->name,
                    'answers_mark_sum' => $answersSubblockMarkSum,
                    'is_passed' => $isPassedSubblock
                ];
            }
            $result[] = [
                'id' => $blockId,
                'min_mark' => $block->min_mark,
                'name' => $block->name,
                'answers_mark_sum' => $answersBlockMarkSum,
                'is_passed' => $allSubblocksPassed,
                'subblocks' => $subblockResults,

            ];
        }
        return json_encode($result);
    }
}