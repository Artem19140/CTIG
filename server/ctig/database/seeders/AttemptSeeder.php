<?php

namespace Database\Seeders;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class AttemptSeeder extends Seeder
{

    public function run(): void
    {
        //Exam::factory(10)->create();
        Attempt::factory(15)
            ->today()
            ->passed()
            ->status(AttemptStatus::Checked)
            ->create();
        Attempt::factory(5)
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
