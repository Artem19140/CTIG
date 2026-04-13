<?php 

namespace App\Domain\Attempt\Query;

use App\Models\Attempt;

class GetDetailedAttemptResultsQuery{
    // public static function execute(Attempt $attempt){
    //     $attemptAnswers = $attempt->answers()->with('taskVariant.task.subblock.block')->get();
    //     $result = [];
    //     $answersByBlock = $attemptAnswers->groupBy(function($answer){
    //         return $answer->taskVariant->task->subblock->block->id;
    //     });

    //     foreach($answersByBlock as $blockId => $blockAnswers){
    //         $block = $blockAnswers->first()->taskVariant->task->subblock->block;
    //         $answersBlockMarkSum = $blockAnswers->sum('mark');

    //         $answersBySubBlock = $blockAnswers->groupBy(function($answer){
    //             return $answer->taskVariant->task->subblock->id;
    //         });

    //         $subblockResults = [];
    //         $allSubblocksPassed = true; 
    //         foreach($answersBySubBlock as $subblockId => $subblockAnswers){
    //             $subblock = $subblockAnswers->first()->taskVariant->task->subblock;
    //             $answersSubblockMarkSum = $subblockAnswers->sum('mark');
    //             $isPassedSubblock = $subblock->min_mark <= $answersSubblockMarkSum;
    //             if(!$isPassedSubblock){
    //                 $allSubblocksPassed = false; 
    //             }
    //             $subblockResults[] = [
    //                 'id' => $subblockId,
    //                 'order' => $subblock->order,
    //                 'min_mark' => $subblock->min_mark,
    //                 'name' => $subblock->name,
    //                 'answers_mark_sum' => $answersSubblockMarkSum,
    //                 'is_passed' => $isPassedSubblock
    //             ];
    //         }
    //         $result[] = [
    //             'id' => $blockId,
    //             'min_mark' => $block->min_mark,
    //             'name' => $block->name,
    //             'answers_mark_sum' => $answersBlockMarkSum,
    //             'is_passed' => $allSubblocksPassed,
    //             'subblocks' => $subblockResults,
    //             'order' => $block->order
    //         ];
    //     }
    //     return $result;
    // }
    public static function execute(Attempt $attempt)
{
    $answers = $attempt->answers()
        ->with('taskVariant.task.subblock.block')
        ->get();

    $byBlock = $answers->groupBy(fn ($answer) =>
        $answer->taskVariant->task->subblock->block->id
    );

    return $byBlock->map(function ($blockAnswers) {

        $block = $blockAnswers->first()
            ->taskVariant->task->subblock->block;

        $bySubBlock = $blockAnswers->groupBy(fn ($answer) =>
            $answer->taskVariant->task->subblock->id
        );

        $subblocks = $bySubBlock->map(function ($subblockAnswers) {

            $subblock = $subblockAnswers->first()
                ->taskVariant->task->subblock;

            $sum = $subblockAnswers->sum('mark');

            return [
                'id' => $subblock->id,
                'order' => $subblock->order,
                'min_mark' => $subblock->min_mark,
                'name' => $subblock->name,
                'answers_mark_sum' => $sum,
                'is_passed' => $subblock->min_mark <= $sum,
            ];
        })->values();

        return [
            'id' => $block->id,
            'min_mark' => $block->min_mark,
            'name' => $block->name,
            'answers_mark_sum' => $blockAnswers->sum('mark'),
            'is_passed' => $subblocks->every(fn ($s) => $s['is_passed']),
            'subblocks' => $subblocks->values(),
            'order' => $block->order,
        ];
    })->values();
}
}