<?php

namespace Tests\Feature\Student;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;

class StudentCreateTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function studentBody(array $overrides = []):array{
        return array_merge([
            "surname" => "Жонбеков",
            "name" => "Жонбек",
            "patronymic" => "Васыл Оглы",
            "dateBirth" => "2008-01-30",
            "surnameLatin" => "Ivanov",
            "nameLatin" => "Ivan",
            "patronymicLatin" => "Ivanovich",
            "passportNumber" => "123456",
            "passportSeries" => "AB",
            "issuedBy" => "МВД по УР",
            "issuesDate" => "2015-05-20",
            "addressReg" => "Moscow, Red Square 1",
            "migrationCardRequisite" => "MC123456789",
            "citizenship" => "Uzbekistan",
            "phone" => "+79161234567",
            "noPatronymic" => false,
        ],$overrides);
    }

    public function postStudent(array $overrides = []){
        return $this
                ->actingAs($this->user)
                ->postJson('api/students', $this->studentBody($overrides))
                ->assertHeader('content-type', 'application/json');
    }

    public function test_success_creating(): void{
        $response =  $this->postStudent();
        $response->assertStatus(201);
        $this->assertDatabaseCount('students', 1);
    }

    public function test_success_18_years(): void{
        $response =  $this->postStudent([
            "dateBirth" => Carbon::now()->subYears(18)->toDateString(),
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseCount('students', 1);
    }

    public function test_fail_not_unique_passport_data(): void{
        $response =  $this->postStudent();
        $response->assertStatus(201);

        $response =  $this->postStudent();
        $response->assertStatus(422);
        $this->assertDatabaseCount('students', 1);
    }

    public function test_fail_under_18_years(): void{
        $response =  $this->postStudent([
            "dateBirth" => Carbon::now()->subYears(18)->addDay()->toDateString(),
        ]);
        $response->assertStatus(422);
        $this->assertDatabaseEmpty('students');
    }

}
