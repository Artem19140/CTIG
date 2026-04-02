<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Organization;
use App\Models\ForeignNational;
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
            'end_time' => fake()->dateTimeBetween('-30 days', '+30 days')->format('Y-m-d'),
            'begin_time_utc' => fake()->dateTimeBetween('-30 days', '+30 days')->format('Y-m-d'),
            'exam_type_id' => ExamType::factory(),
            'creator_id' => User::factory(),
            'capacity'=>fake()->numberBetween(5, 20),
            'address_id' => Address::factory(),
            'organization_id' => Organization::factory()
        ];
    }

    public function inFuture(){
        return $this->state(function(){
            return[
                'begin_time_utc' => Carbon::now()->addMinutes(10),
            ];
        });
    }

    public function now(){
        return $this->state(function(){
            return[
                'begin_time_utc' => Carbon::now()->subMinutes(10),
            ];
        });
    }

    public function inPast(int $duration){
        return $this->state(function() use($duration){
            return[
                'begin_time_utc' => Carbon::now()->subMinutes(
                    $duration + 10
                ),
            ];
        });
    }

    public function cancelled(){
        return $this->state(function(){
            return[
                'is_cancelled' => true,
            ];
        });
    }

    public function withForeignNationals(int $count = 3)
    {
        return $this
            ->state([
                'capacity' => $count,
            ])
            ->hasAttached(ForeignNational::factory()->count($count));
    }    

    public function withRandomCreator(): ExamFactory{
        return $this->state(function(){
            return[
                'creator_id'=>User::inRandomOrder()->first()->id
            ];
        });
    }
}
