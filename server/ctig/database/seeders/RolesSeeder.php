<?php

namespace Database\Seeders;

use App\Enums\EmployeeRole;
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

        foreach (EmployeeRole::cases() as $role) {
            Role::firstOrCreate(['name' => $role->value]);
        }
    }
}
