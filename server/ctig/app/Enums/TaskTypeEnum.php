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
            self::Speaking => false,
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

    public function hasAnswers(): bool{
        return match($this){
            self::Speaking => false,
            default => true
        };
    }

    public static function autoCheckTypes(): array
    {
        return array_map(fn($case) => $case->value, array_filter(self::cases(), fn($case) => $case->autoCheck()));
    }

    public static function manualCheckTypes(): array
{
    return array_map(
        fn($case) => $case->value,
        array_filter(self::cases(), fn($case) => !$case->autoCheck())
    );
}
}
