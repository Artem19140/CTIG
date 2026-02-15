<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskVariant>
 */
class TaskVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contain' => fake()->sentence(),
            'fipi_guid' => fake()->uuid(),
            'task_id' => Task::factory(),
            'mark' => fake()->numberBetween(1,2)
        ];
    }

    public function notActive(){
        return $this->state(function(){
            return[
                'is_active'=>false
            ];
        });
    }

    public function group(int $groupId){
        return $this->state(function()use($groupId){
            return[
                'group_id'=>$groupId
            ];
        });
    }
}
