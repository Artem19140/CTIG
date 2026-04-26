<?php

namespace Database\Factories;

use App\Models\Center;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'surname' => fake()->randomElement(
                [
                    'Рязанова',
                    'Петрова',
                    'Привалова',
                    'Шинкевич',
                    'Майорова']
                ),
            'name' => fake()->firstNameFemale(),
            'patronymic' => fake()->randomElement(
                [
                    'Юрьевна',
                    'Сергеевна',
                    'Вячеславовна',
                    'Леонидовна',
                    'Максимовна']
                ),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'has_to_change_password' => false,
            'is_active' => true,
            'remember_token' => Str::random(10),
            'center_id' => Center::factory(),
            'job_title'=>  fake()->randomElement(['Специалист центра тестирования иностранных граждан','Директор центра тестирования иностранных граждан', 'Сотрудник центра тестирования иностранных граждан', 'Тестер центра тестирования иностранных граждан'])
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function notActive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
