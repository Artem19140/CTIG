<?php

namespace App\Actions\Attempt;

class FormAttemptExamVariantAction{
    public function execute($tasks, $exam, $attempt, $student){
        $groups = [];
        $examVariant = [];
        foreach($tasks as $task){    
            $variants = $task->variants
                        ->where('is_active',true);
                        
            $variant = $variants
                        ->whereIn('group_number', $groups)
                        ->first();

            if(!$variant){
                $variant = $variants->random();
            }

            if($variant->group_number && !\in_array($variant->group_number, $groups)){
                $groups[] = $variant->group_number;
            }

            $examVariant[] = [
                'exam_id' => $exam->id,
                'task_variant_id' => $variant->id,
                'attempt_id' => $attempt->id, 
                'student_id' =>$student->id, 

            ];
        }
        return $examVariant;
    }
}