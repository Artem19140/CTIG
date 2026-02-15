<?php

namespace Database\Factories;

use App\Models\ExamBlock;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subblock>
 */
class SubblockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'exam_block_id' => ExamBlock::factory(),
            'creator_id' => User::factory()
        ];
    }

    public function withRandomCreator(): SubblockFactory{
        return $this->state(function(){
            return[
                'creator_id'=>User::inRandomOrder()->first()->id
            ];
        });
    }
}
