<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use WithoutModelEvents;
    public function run(): void
    {

        $roleSpecialist = Role::factory()->operator()->create();

        $roleExaminer = Role::factory()->examiner()->create();

        $roleScheduler = Role::factory()->scheduler()->create();

        $roleDirector = Role::factory()->director()->create();

        $roleOrgAdmin = Role::factory()->orgAdmin()->create();

        $SuperAdmin = Role::factory()->superAdmin()->create();
    }
}
