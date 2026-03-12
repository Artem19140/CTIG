<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExamType;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\Address;
use App\Models\User;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        
        Address::create([
            'address'=>'Ижевск, Университетская, 1/корпус 2/каб. 124',
            'max_capacity' => 15,
            'organization_id' => Organization::inRandomOrder()->first()->id
        ]);
        Exam::factory(10)->withRandomCreator()->create([
            'address_id' => Address::inRandomOrder()->first()->id
        ]);

    }
}
