<?php

namespace Database\Factories;

use App\Models\ExamBlock;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    public function withRandomCreator(): TaskFactory{
        return $this->state(function(){
            return[
                'creator_id'=>User::inRandomOrder()->first()->id
            ];
        });
    }

    public function withRandomExamBlock(): TaskFactory{
        return $this->state(function(){
            return[
                'exam_block_id'=>ExamBlock::inRandomOrder()->first()->id
            ];
        });
    }
    public function noHumanCheck(){
        return $this->state(function(){
            return[
                'need_human_check' => 0
            ];
        });
    }

    public function definition(): array
    {
        
        return [
            'contain' =>  fake()->sentence,
            'need_human_check' => fake()->numberBetween(0,1),
            'creator_id' => User::factory(),
            'exam_block_id' => ExamBlock::factory()
        ];
    }
}
