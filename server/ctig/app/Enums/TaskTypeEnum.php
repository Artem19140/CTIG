<?php

namespace App\Enums;

enum TaskTypeEnum:string
{
    case SingleChoice = 'single_choice';
    case TextInput = 'text_input';
    case Essay = 'essay';
    case Speaking = 'speaking';

    public function autoCheck():bool {
        return match($this){
            self::SingleChoice => true,
            self::TextInput => true,
            self::Speaking => true,
            self::Essay => false
        };
    }

    public function allowMiltiplyAnswers(): bool{
        return match($this){
            self::SingleChoice => false,
            self::TextInput => false,
            self::Speaking => false,
            self::Essay => false
        };
    }
}
