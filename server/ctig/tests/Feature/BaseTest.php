<?php

// namespace Tests\Feature;
// use App\Enums\EmployeeRole;
// use App\Models\Center;
// use App\Models\Employee;
// use Database\Seeders\RolesSeeder;
// use Tests\TestCase;

// class BaseTestCase extends TestCase{
//     protected Center $center;
//     protected function setUp(): void{
//         parent::setUp();
//         $this->seed(RolesSeeder::class);
//     }
//     protected function actor(EmployeeRole ...$roles){
        
//         return Employee::factory()
//             ->create(['center_id' => $this->center->id]);
//     }

//     protected function createCenter(){
//         return Center::factory()->create();
//     }
// }