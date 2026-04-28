<?php

namespace Database\Seeders;

use App\Models\ForeignNational;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ForeignNationalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use WithoutModelEvents;
    public function run(): void
    {
        ForeignNational::factory(300) 
            ->withRandomCreator()
            ->create();
    }
}
