<?php

namespace App\Enums;

enum TaskType:string
{
    case SingleChoice = 'single_choice';
    case TextInput = 'text_input';
    case Essay = 'essay';

    public function autoCheck():bool {
        return match($this){
            self::SingleChoice, self::TextInput => true,
            self::Essay => false
        };
    }

    public function allowMiltiplyAnswers(): bool{
        return match($this){
            self::SingleChoice => false,
            default => true
        };
    }
}
