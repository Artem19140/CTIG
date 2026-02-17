<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\ExamType;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    

    public function definition(): array
    {
        return [
            'begin_time' => fake()->dateTimeBetween('-30 days', '+30 days')->format('Y-m-d'),
            'exam_type_id' => ExamType::inRandomOrder()->first()->id,
            'creator_id' => User::factory(),
            'capacity'=>fake()->numberBetween(5, 20),
            'address_id' => Address::factory(),
            'date' => fake()->dateTimeBetween('-1 week', '+1 week')->format('Y-m-d'),
        ];
    }

    public function inFuture(){
        return $this->state(function(){
            return[
                'begin_time' => Carbon::now()->addDay(),
                'end_time' => Carbon::now()->addDay()->addHour()
            ];
        });
    }

    public function inPast(){
        return $this->state(function(){
            return[
                'begin_time' => Carbon::now()->subDay(),
                'end_time' => Carbon::now()->subDay()->addHour()
            ];
        });
    }

    public function withStudents(int $count = 3)
    {
        return $this
            ->state([
                'capacity' => $count,
            ])
            ->hasAttached(Student::factory()->count($count));
    }    

    public function withRandomCreator(): ExamFactory{
        return $this->state(function(){
            return[
                'creator_id'=>User::inRandomOrder()->first()->id
            ];
        });
    }
}
