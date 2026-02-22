<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamType>
 */
class ExamTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Внж',
            'short_name' => 'Внж',
            'level' => 1, 
            'certificate_name' => 'Имя сертификата',
            'duration' => 80
        ];
    }

    public function notActive(){
        return $this->state(function(){
            return[
                'is_active'=>false
            ];
        });
    }
}
