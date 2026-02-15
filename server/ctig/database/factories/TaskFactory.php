<?php

namespace Database\Factories;

use App\Models\Subblock;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Enums\TaskType;
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

    public function withRandomSublock(): TaskFactory{
        return $this->state(function(){
            return[
                'subblock_id'=>Subblock::inRandomOrder()->first()->id
            ];
        });
    }

    public function definition(): array
    {
        
        return [
            'creator_id' => User::factory(),
            'subblock_id' => Subblock::factory(),
            'order' => 1,
            'type' => fake()->randomElement(TaskType::cases())->value,
        ];
    }
}
