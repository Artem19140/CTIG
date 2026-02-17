<?php

namespace App\Enums;

enum TaskTypeEnum:string
{
    case SingleChoice = 'single_choice';
    case TextInput = 'text_input';
    case Essay = 'essay';
    case OnlyMark = 'only_mark';

    public function autoCheck():bool {
        return match($this){
            self::SingleChoice => true,
            self::TextInput => true,
            self::OnlyMark => true,
            self::Essay => false
        };
    }

    public function allowMiltiplyAnswers(): bool{
        return match($this){
            self::SingleChoice => false,
            self::TextInput => false,
            self::OnlyMark => false,
            self::Essay => false
        };
    }
}
