<?php

namespace Database\Seeders;

use App\Models\Center;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Address;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        
        Address::create([
            'address'=>'Ижевск, Университетская, 1/корпус 2/каб. 124',
            'max_capacity' => 15,
            'center_id' => Center::inRandomOrder()->first()->id
        ]);

        Address::create([
            'address'=>'Ижевск, Удмуртская, 2 каб. 542',
            'max_capacity' => 14,
            'center_id' => Center::inRandomOrder()->first()->id
        ]);
        // Exam::factory(10)->withRandomCreator()->create([
        //     'address_id' => Address::inRandomOrder()->first()->id
        // ]);

    }
}
