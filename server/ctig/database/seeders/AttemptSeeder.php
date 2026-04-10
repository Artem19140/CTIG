<?php

namespace Database\Seeders;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Center;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ForeignNational;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class AttemptSeeder extends Seeder
{

    public function run(): void
    {
        Enrollment::factory(30)->hasPayment()->create([
            'center_id' => Center::inRandomOrder()->first()->id,

            'creator_id' => User::inRandomOrder()->first()->id,
        ]);
        //Exam::factory(10)->create();
        Attempt::factory(150)
            ->today()
            ->passed()
            ->status(AttemptStatus::Checked)
            ->create();
        Attempt::factory(50)
            ->today()
            ->failed()
            ->status(AttemptStatus::Checked)
            ->create();
        Attempt::factory(2)
            ->today()
            ->failed()
            ->status(AttemptStatus::Banned)
            ->create();
    }
}
