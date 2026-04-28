<?php

namespace Database\Seeders;

use App\Models\Attempt;
use App\Models\Center;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class AttemptSeeder extends Seeder
{
    use WithoutModelEvents;
    public function run(): void
    {
        Enrollment::factory(30)->hasPayment()->create([
            'center_id' => Center::inRandomOrder()->first()->id,

            'creator_id' => User::inRandomOrder()->first()->id,
        ]);
        Attempt::factory(150)
            ->today()
            ->passed()
            ->checked()
            ->create();
        Attempt::factory(50)
            ->today()
            ->failed()
            ->checked()
            ->create();
        Attempt::factory(2)
            ->today()
            ->failed()
            ->banned()
            ->create();
    }
}
